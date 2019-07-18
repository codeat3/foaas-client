# Changelog

All notable changes to `foaas-client` will be documented in this file

## 1.0.1 - 2019-07-18

##### Updates
* added typehint for available methods to the client
* added travis-ci yaml

## 1.0.0 - 2019-07-12

##### Initial Release
* a very basic wrapper around FOAAS api
* helpers for the type of content needed - getAsText(), getAsJson(), getAsXml(), getAsHtml(), getAsArray()
* added exceptions on invalid method calls & invalid number of arguments passed
* support for custom response type by implementing `\Codeat3\FoaasClient\Response\FoaasResponse`
* added `decency` as a filter which filters the `F` word by adding special characters
