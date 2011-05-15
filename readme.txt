=== GERRYWORKS Post by Mail ===
Contributors: gerryworks
Donate link: http://gerryworks.be/me-soutenir
Tags: publish, post, emailpost
Requires at least: 2.8
Tested up to: 3.1.2
Stable tag: 1.0

Replace and add security to the default post by mail included in WP. Pour remplacer de fa�on s�re la publication par mail d�j� pr�sent dans WP.

== Description ==
**English**

This plugin exists in order to offset some deficiencies of the Wordpress post by mail features.
It would be useful for those who want to post on their blog by mail or those who want to allow people to publish post anonymously on their blog by sending emails.

Here's the list of added  or modified features

* Mails from users are automaticaly published under their author name. Mails from non users are, depending on your settings, publised automaticaly or save in pending mode.
* Posibility to specify categorie for the article sent by mail. To specify this, the mail subject have to fit this format `Category]] The title of the post`.
* Spam filtering
* Detection of Youtube, Vimeo, Wat.tv and Dailymotion video links in the mail
* All html tags are deleted, `<div>` tags are replaces by `<p>` tags.
* All tags styles are deleted.
* YouTube and dailymotion links are converted into the youtube, dailymotion embed flash player.
* Possibility to set status of articles posted by mail.

**FRANCAIS**

Ce plugin a �t� cr�� dans le but de compenser certains manquements de la fonctionnalit� de publication par mail de Wordpress.
Il pourrait �tre utilse pour ceux qui veulent publier des articles sur leurs blog en envoyant un simple mail ou peur ceux qui veulent permettre aux gens de publier des articles anonymement sur leur blog en envoyant un mail.

Voici une liste des fonctionnalit�s ajout�es ou modifi�es :

* Mail des utilisateurs membres automatiquement publi�s sous leurs noms d�auteurs. Les mails des non membres sont soit publi�s automatiquement, soit mis en attente de mod�ration.
* La possibilit� de sp�cifier une cat�gorie pour l�article envoy� par mail. Pour ce faire, le sujet doit �tre sous le format `Categorie]] Titre de l�article`.
* Filtrage des mails spams.
* D�tection des liens videos Youtube, Vimeo, Wat.tv, Blip.tv et Dailymotion contenus dans le mail.
* Toutes les balises sont supprim�s, les balises `<div>` sont remplac�es par les balises `<p>`
* Les styles appliqu�es aux tags sont supprim�s.
* Conversions des liens Youtube ou Dailymotion en vid�os int�gr�es dans le lecteur flash Youtube ou Dailymotion.
* Possibilit� de configurer le statut des articles post�s par mail.

== Installation ==
**ENGLISH**

This section describes how to install this plugin and get it working.

1. Upload `gw-post-by-mail` directory (including all files within) to your plugin directory, this must be the `/wp-content/plugins/` directory by default.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin settings by visiting `Settings=>GERRYWORKS Post by Mail`.
1. Configure server settings in order to make this work.

**FRENCH**

Cette section explique comment installer ce plugin et le faire fonctionner

1. Charge le repertoire `gw-post-by-mail` (avec tous les fichiers qu'il contient) dans le r�pertoire des plugins, ce repertoire est `/wp-content/plugins/`  par defaut.
1. Active le plugin via le menu 'Extensions' de Wordpress.
1. Configure les reglages du plugin en visitant la page `Reglages=>GERRYWORKS Post by Mail`
1. Configure les reglages du serveur pour que tout fonctionne.

== Frequently Asked Questions ==

= Does it work?/ Ca marche vraiment?  =
Yes it work, you can try it. Just follow the demo explanation.

Oui �a marche. Tu peux l'essayer en suivant les explications de la version demo

= Can we add attachement? / On peut ajouter des pi�ces jointes? =
Yes you can, but this version don't support the publishing of attachements.

Oui, cependant cette version ne supporte pas encore la publication des pi�ces jointes.

= Wat is your advice? / Quel est votre conseil? =
Try it, i'm sure it will be very usefull.

Essayez le, je suis s�r ce plugin peut �tre tr�s utile.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)
2. This is the second screen shot

== Changelog ==

== Upgrade Notice==

== Demo ==
**ENGLISH**

Follow these steps in order to try GERRYWORKS Post by Mail.

* Send a mail to `wordpress@demos.gerryworks.be`
* Put the mail subject in this mail. You can choose the category of your post, use this format 'category]] Title of my post'.
For demo purpose, we have created  the 'demo' category. so replace 'category' in the example above by 'demo'.
* Put all that you want in this mail. Text, Youtube, dailymotion or Vimeo url, mails adress, url.
* Send all this.
* Check to see  your post published. Visit the [Gerryworks Wordpress plugins demo](http://wordpress.demos.gerryworks.be/ "The GERRYWORKS Wordpress Plugins demos") for verification.

**FRANCAIS**

Veuillez suivre ces diff�rentes �tapes pour tester GERRYWORKS Post by Mail

* Envoi un email � `wordpress@demos.gerryworks.be`
* Met un sujet � ce mail. Pour choisir la cat�gorie pour ton article, utilise ce format 'categorie]] Titre de ton article'.
Pour la demo nous avons cr�� la cat�gorie 'demo'. Veullez donc remplacer 'categorie' par demo dans l'exemple donn� plus haut.
* Met tout ce que tu veux dans ton mail. Du texte, des videos Youtube, Dailymotion ou Vimeo, des adresses mails, des liens.
* Envoie ton mail.
* V�rifie pour voir ton article publi�. Visite [Gerryworks Wordpress plugins demo](http://wordpress.demos.gerryworks.be/ "The GERRYWORKS Wordpress Plugins demos") pour v�rifier.