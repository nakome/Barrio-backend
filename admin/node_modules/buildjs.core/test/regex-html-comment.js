var test = require('tape');
var regex = require('../regexes').includeHtmlComment;

test('<!--= filename.html -->', function(t) {
  var match;

  t.plan(4);
  match = regex.exec(t.name);

  t.ok(match);
  t.equal(match[1], ''); // leader
  t.equal(match[2], ''); // directive
  t.equal(match[3], 'filename.html'); // filename
});

test('<!--= path/filename.html -->', function(t) {
  var match;

  t.plan(4);
  match = regex.exec(t.name);

  t.ok(match);
  t.equal(match[1], ''); // leader
  t.equal(match[2], ''); // directive
  t.equal(match[3], 'path/filename.html'); // filename
});
