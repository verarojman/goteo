<?php $this->layout('faq/layout') ?>

<?php $this->section('faq-content') ?>

  <div id="breadcrumb" class="container-fluid">
	    <div class="container">
			<a href="/faq">FAQs</a><span class="slash"> / </span><a href="<?= '/faq/'.$this->faq_section->slug ?>"><?= $this->faq_section->name ?></a><span class="slash"> / </span><?= $this->faq->title ?>
	    </div>
    </div>
    <header id="header_faqs" class="container-fluid <?= $this->faq_section->slug ?>">
		<div class="container">
			<div class="row barra_superior">
				<form class="col-sm-6 buscador_faqs">
					<label>
						<span class="a-hidden"><?= $this->text('faq-search') ?></span>
						<input type="search" class="search-field" name="" placeholder="¿Qué estás buscando?">
					</label>
					<button class="search-submit icon-search"><span class="a-hidden"><?= $this->text('regular-search') ?></span></button>
				</form>
				<div class="col-sm-6 hidden-xs" style="text-align: right;">
					<a href="/project/create" target="_blank" class="btn btn-fashion"><?= $this->text('regular-create') ?></a>
				</div>                
			</div>
			<div class="row">
				<h3><a href="<?= '/faq/'.$this->faq_section->slug ?>"><?= $this->faq_section->name ?></a></h3>
				<h1><?= $this->faq->title ?></h1>
			</div>
		</div>
    </header>
    <section class="container pas3">
      	<div class="row">
	        <article class="col-sm-8 col-sm-offset-1 col-sm-push-3">
		       	<?= $this->markdown($this->faq->description) ?>
	    		<footer class="no_resuelto">
		    		<a href="/contact" class="btn">¿NO HEMOS RESUELTO TU DUDA?</a>
		    	</footer>
	        </article>
	        <aside class="col-sm-3 col-sm-pull-9" id="accordion">
	      		<?php foreach ($this->subsections as $subsection): ?>
    	
		    		<?php $faq_subsection=$subsection->getFaqbySubsection(5); ?>
		      		<section>
			      		<h3 class="collapsed" data-toggle="collapse" data-target="#collapse-<?= $subsection->id ?>" aria-expanded="true" aria-controls="collapse-<?= $subsection->id ?>"><?= $subsection->name ?></h3>
			      		<ul id="collapse-<?= $subsection->id ?>" class="collapse" aria-labelledby="heading-<?= $subsection->id ?>" data-parent="#accordion">
			      			<?php foreach($faq_subsection as $faq): ?>
						        <?php $faq_id= $faq->slug ? $faq->slug : $faq->id; ?>
						    	<?php if($faq->id==$this->faq->id): ?>
						 			<li class="select"><?= $faq->title ?></li>
						 		<?php else: ?>
						 			<li>
						 				<a href="<?= '/faq/'.$this->faq_section->slug.'/'.$this->faq->id ?>">
						 				<?= $faq->title ?>	
						 				</a>
						 			</li>
						 		<?php endif; ?>
						 	<?php endforeach; ?>
				        </ul>
		      		</section>

	      		<?php endforeach; ?>
	      	</aside>
    	</div>
    </section>
   
<?php $this->replace() ?>
