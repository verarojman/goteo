<?php

$this->layout('admin/container');

$this->section('admin-container-head');

?>

<h2><?= $this->text('admin-faq-subsections') ?></h2>

<?= $this->insert('admin/partials/search_box') ?>

<?= $this->supply('admin-faq-head') ?>

<?php $this->replace() ?>

<?php $this->section('footer') ?>

<script type="text/javascript" defer src="<?= $this->asset('js/admin/faqs.js') ?>"></script>

<?php $this->append() ?>
