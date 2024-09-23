<?php

$context = Timber::context();

$context['post'] = Timber::get_post();

Timber::render('index.twig', $context);
