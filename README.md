# redcap-geochart-plugin

## Installation ##

Past the folder "googlechart" in your plugins directory.

Go to http(s)://yourinstanceofredcap/plugins/googlechart/index.php?pid=YourProjectID for creating the sqliteDB. Refresh the page to have the link to the sqliteDB.

## Customization ##

in URL you can add parameter:

    map= : to focus on a continent (Europe, US) or a country
    Exemple :

http(s)://yourinstanceofredcap/plugins/googlechart/index.php?pid=YourProjectID&map=150

http(s)://yourinstanceofredcap/plugins/googlechart/index.php?pid=YourProjectID&map=FR

150 is code for Europe, FR for France

you can find you code here : https://developers.google.com/chart/interactive/docs/gallery/geochart#continent-hierarchy-and-codes

    Provinces=YES for activate provinces mode

http(s)://yourinstanceofredcap/plugins/googlechart/index.php?pid=YourProjectID&map=FR&provinces=YES
