<?php

// queue_css_file('eman-plugin');
head_css();
echo head(array('title' => 'Eman Citation'));

echo flash(); 

echo $preview;
?>
<br /><br />
Jetons disponibles (certains jetons ne sont pas disponibles sur les fichiers et les collections) :
<ul>
<li>Titre <b>{titre}</b></li>
<li>Auteur <b>{createur}</b></li>
<li>Titre <b>{editeur}</b></li>
<li>Date <b>{date}</b></li>
<li>Sous Titre <b>{soustitre}</b></li>
<li>Auteur Description <b>{auteurdesc}</b></li>
<li>Auteur Transcription <b>{auteurtrans}</b></li>
<li>Adresse du document <b>{url}</b></li>
</ul>
</em>
<h3>Saisissez les modèles de vos blocs citation ci-dessous :</h3>
<em><strong>Vous devez ajouter les séparateurs (virgule, point virgule) et mettre vous même les retours à la ligne. Attention, si vous ajoutez une virgule dans le modèle, elle apparaitra tout le temps - même si le champ précédent n'est pas rempli.</strong></em>
<?php 
echo $content;
echo foot(); 
?>

