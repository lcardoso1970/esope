#!/usr/bin/env python

# Generates docs/download.html
# Run:
# ./generate-download.py > docs/download.html

import json

releases = json.load(file('releases.json'))

def file_links(release):
  v = release['version']
  return ['<a href="%(v)s/%(f)s">%(f)s</a>' % {
    'f': f, 'v': v} for f in release['files']]


# Validation of releases.json
for idx, release in enumerate(releases):
  if idx == 0: continue
  assert 'version' in release, 'Release missing version: %s' % release
  assert 'files' in release, 'Release missing files: %s' % release
  assert release['version'] < releases[idx - 1]['version'], (
      'Releases should be in reverse chronological order in releases.json')

current_html = '<p>' + ('</p><p>'.join(file_links(releases[0]))) + '</p>'


previous_lis = []
for release in releases[1:]:
  previous_lis.append('<li>%(v)s: %(files)s (<a href="%(v)s/">%(v)s docs</a>)' % {
      'v': release['version'],
      'files': ', '.join(file_links(release))
    })


print '''
<!--#include virtual="header.html" -->

<!--
  DO NOT EDIT THIS FILE!

  This file is generated by generate-download.py.
-->

<script src="modernizr.custom.18445.js"></script>
<p>The current version of dygraphs is <b>%(version)s</b>. Most users will want to download minified files for this version:</p>

<div id="current-release" class="panel">
%(current_html)s
</div>

<p>There's a hosted version of dygraphs on <a href="https://cdnjs.com/libraries/dygraph">cdnjs.com</a>:</p>

<pre>&lt;script src="//cdnjs.cloudflare.com/ajax/libs/dygraph/%(version)s/dygraph-combined.js"&gt;&lt;/script&gt;</pre>

<p>You can install dygraphs using <a href="https://www.npmjs.org/package/dygraphs">NPM</a> or <a href="http://bower.io/search/?q=dygraphs">Bower</a>.</p>

<p>To install using NPM:</p>
<pre>$ npm install dygraphs
# dygraphs is now in node_modules/dygraphs/dygraph-combined.js</pre>

<p>To install using bower:</p>
<pre>$ bower install dygraphs
# dygraphs is now in bower_components/dygraphs/dygraph-combined.js</pre>

<p>For dev (non-minified) JS, see <a href="https://github.com/danvk/dygraphs/blob/master/dygraph-dev.js">dygraph-dev.js</a> on <a href="https://github.com/danvk/dygraphs/">github</a>.</a>

<p>To generate your own minified JS, run:</p>

<pre>git clone https://github.com/danvk/dygraphs.git
./generate-combined.sh
</pre>

<p>This will create a dygraph.min.js file in the dygraphs directory.</p>

<p>You may also download files for previously-released versions:</p>

<ul>
%(previous_lis)s
</ul>

<p>See <a href="/versions.html">Version History</a> for more information on each release.</p>


<!--#include virtual="footer.html" -->
''' % {
    'version': releases[0]['version'],
    'current_html': current_html,
    'previous_lis': '\n'.join(previous_lis)
    }
