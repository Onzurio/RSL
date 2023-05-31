<?php
session_start();

require_once('libs/modele.php');
require_once('vue/vue.php');
require_once('controleur.php');
require_once('libs/config.php');

$controleur = new Controleur();
$controleur->gererRequete();
