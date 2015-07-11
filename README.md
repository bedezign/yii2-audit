# Yii2 Audit Documentation

For details on how to manage this site, read the [documentation](http://jekyllrb.com/)

## Running Locally

```
jekyll serve --host 0.0.0.0 --port 80
```

## Include Markdown from HTML File

```
{% capture contents %}{% include test.md %}{% endcapture %}
{{ contents | markdownify }}
```