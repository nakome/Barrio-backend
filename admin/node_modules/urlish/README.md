
# urlish

This is a simple module that parses an input string to determine if it
matches with a typical url syntax.  This is not a strict URL parser, and if
that behaviour is desired I would recommend looking at the core node
[url](http://nodejs.org/api/url.html) module.

This module was written to satisfy the use case of
[getit](https://github.com/DamonOehlman/getit) where url-like syntaxes
are used.


[![NPM](https://nodei.co/npm/urlish.png)](https://nodei.co/npm/urlish/)

[![Build Status](https://api.travis-ci.org/DamonOehlman/urlish.svg?branch=master)](https://travis-ci.org/DamonOehlman/urlish) [![bitHound Score](https://www.bithound.io/bitbucket/DamonOehlman/urlish/badges/score.svg)](https://www.bithound.io/bitbucket/DamonOehlman/urlish) 

## So what is URLish?

In very simple terms we are looking for something that matches the
following format:

```
[scheme]://[hostname](:[port])?(/[path])?
```

At this stage, things like usernames and passwords are not looked at
and no querystring or hash parsing is attempted either.

## License(s)

### MIT

Copyright (c) 2016 Damon Oehlman <damon.oehlman@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
