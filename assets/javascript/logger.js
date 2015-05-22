(function (window, document) {
    if (!window.jsLogger) {
        window.jsLogger = new function() {
            // The url to post captured log entries to (default assumes the auditing module was added as "auditing").
            this.logUrl = '/auditing/javascript/log';

            // The types that should be sent to the backend.
            this.captureTypes = ['warn', 'error', 'onerror'];

            // True if you also want any error to be forwarded to the appropriate console function
            this.consoleOutput = typeof window.console !== 'undefined' && typeof window.console.log !== 'undefined';

            // True to pass on the error to the previously active handler
            this.chainErrors = true;

            var previousErrorHandler, errorHandler = function(message, file, line, col, error) {
                // Also send the the error object
                window.jsLogger.capture('onerror', message, {error: error}, file, line, col);
                if (typeof previousErrorHandler == 'function' && this.chainErrors)
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
                    previousErrorHandler = window.onerror
                window.onerror = errorHandler;
            };

            this.attachDojoErrorHandler = function() {
                /*dojo.connect(console, "error", function(error, trace) {
                    window.jsLogger.capture('onerror', error.message, {error: error, trace: trace}, error.fileName, error.lineNumber, error.col);
                });*/
            }

            this.capture = function(type, message, data, file, line, col) {
                if (window.XMLHttpRequest && this.captureTypes.indexOf(type.toLowerCase()) != -1) {
                    var xhr = new XMLHttpRequest(),
                        log = {type: type, message: message, data: data, file: file, line: line, col: col};
                    if (window.auditEntry)
                        log.auditEntry = window.auditEntry;

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
            Object.defineProperty(Error.prototype, 'toJSON', { value: function () {
                var alt = {}; Object.getOwnPropertyNames(this).forEach(function (key) { alt[key] = this[key]; }, this); return alt;
            }, configurable: true });

            this.attachErrorHandler();
        };
    }
})(window, document);