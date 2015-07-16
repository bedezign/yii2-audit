(function (window, document) {
    if (!window.jsLogger) {
        window.jsLogger = new function() {
            // The url logs should be sent to. Filled in the capture function.
            this.logUrl = null;

            // The types that should be sent to the backend.
            this.captureTypes = ['warn', 'error', 'onerror'];

            // True if you also want any error to be forwarded to the appropriate console function
            this.consoleOutput = typeof window.console !== 'undefined' &&
                typeof window.console.log !== 'undefined' && typeof window.console.log.apply !== 'undefined';

            // True to pass on the error to the previously active handler
            this.chainErrors = true;

            var previousErrorHandler, errorHandler = function(message, file, line, col, error) {
                // Also send the the error object
                window.jsLogger.capture('onerror', message, {error: window.jsLogger.errorToString(error)}, file, line, col);
                if (typeof previousErrorHandler == 'function' && window.jsLogger.chainErrors)
                    return previousErrorHandler(message, file, line, col, error);
            };

            this.info = function (message, data) {
                if (this.consoleOutput) console.info.apply(console, arguments);
                this.capture('info', message, data);
            };

            this.log = function(message, data) {
                if (this.consoleOutput) console.log.apply(console, arguments);
                this.capture('log', message, data);
            };

            this.warn = function (message, data) {
                if (this.consoleOutput) console.warn.apply(console, arguments);
                this.capture('warn', message, data);
            };

            this.error = function (message, data) {
                if (this.consoleOutput) console.error.apply(console, arguments);
                this.capture('error', message, data);
            };

            this.attachErrorHandler = function() {
                if (typeof previousErrorHandler != 'function' || previousErrorHandler != errorHandler)
                    previousErrorHandler = window.onerror;
                window.onerror = errorHandler;
            };

            this.capture = function(type, message, data, file, line, col) {
                if (!this.logUrl)
                    this.logUrl = window.auditUrl || 'index.php?r=audit/js-log/index';

                if (window.XMLHttpRequest && this.captureTypes.indexOf(type.toLowerCase()) != -1) {
                    var xhr = new XMLHttpRequest(),
                        log = {type: type, message: message, data: data, file: file, line: line, col: col};
                    if (window.auditEntry)
                        log.auditEntry = window.auditEntry;

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4) {
                            data = JSON.parse(xhr.responseText);
                            if (data.entry) window.auditEntry = data.entry;
                        }
                    };
                    xhr.open('POST', this.logUrl);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send('data=' + encodeURIComponent(JSON.stringify(log)));
                }
                return true;
            };

            // Wrap console functions in a native function to make sure they have an "apply" function.
            // IE9 console functions are objects and don't have it (http://stackoverflow.com/a/5539378/50158)
            if (Function.prototype.bind && window.console && typeof console.log == "object") {
                ["log","info","warn","error","assert","dir","clear","profile","profileEnd"]
                .forEach(function (method) { console[method] = this.bind(console[method], console); }, Function.prototype.call);
            }

            // Allow for stringify'ing Error objects (http://stackoverflow.com/a/18391400/50158)
            this.errorToString = function(err, filter, space) {
              var plainObject = {};
              if (Object.getOwnPropertyNames) Object.getOwnPropertyNames(err).forEach(function(key) { plainObject[key] = err[key];});
              else for (var k in err) { if (err.hasOwnProperty(k)) plainObject.push(k); }
              return JSON.stringify(plainObject, filter, space);
            };

            if (!Array.prototype.indexOf) { Array.prototype.indexOf = function(obj, start) { for (var i = (start || 0), j = this.length; i < j; i++) { if (this[i] === obj) { return i; } } return -1; } }

            this.attachErrorHandler();
        };
    }
})(window, document);
