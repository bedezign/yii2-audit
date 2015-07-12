---
layout: default
title: Screen Shots
permalink: /screenshots/
---

# Screen Shots

<div class="row">
    {% for screenshot in site.data.screenshots %}
    <div class="col-md-3">
        <h3>{{screenshot.name}}</h3>
        <div class="thumbnail">
            <a href="{{screenshot.url}}" class="fancybox" rel="screenshots"><img src="{{screenshot.url}}" alt="{{screenshot.name}}"></a>
        </div>
    </div>
    {% endfor %}
</div>
