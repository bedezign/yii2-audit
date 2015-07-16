---
layout: default
title: SOAP Panel
permalink: /docs/soap-panel/
---

# SOAP Panel

Just like cURL, this is a speciality panel, designed to help you track data of the SOAP requests you make.


## Usage

To activate the SOAP logging functionality you either create an instance of the included `SoapClient`(`bedezign\yii2\audit\components\SoapClient`) instead of the default one (`\SoapClient`) or derive from it to use as a basis for your own class.

It will take care of logging from there on. 
All `SoapFault`'s are automatically logged an error as well. A link is provided from the Soap panel to the error. No need to add this yourself.
