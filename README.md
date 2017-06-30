# Citation

This module provides a page and a basic token system to setup a 'How to cite this document' block for Items, Files and Collections.

This block can then be output in your theme by calling :

EmanCitationPlugin::citationTokens(metadata('recordType', 'recordId), <type>).

<type> begin one of "item", "collection" or "file".

