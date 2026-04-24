<?php

$config = config(Config\Pager::class);

if (property_exists($config, 'surroundCount'))
{
	$surroundCount = $config->surroundCount;
}
else
{
	$surroundCount = 3;
}

?>

<?php $pager->setSurroundCount($surroundCount);?>
<nav aria-label="<?= lang('base.pageNavigation');?>">

  <ul class="pagination">

	<?php if ($pager->hasPrevious()) : ?>

		<li class="page-item">
			<a class="page-link" href="<?= $pager->getFirst();?>" aria-label="<?= lang('base.first');?>">
				<span aria-hidden="true"><?= lang('base.first');?></span>
			</a>
		</li>
		
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getPrevious();?>" aria-label="<?= lang('base.previous');?>">
				<span aria-hidden="true">&laquo;</span>
			</a>
		</li>
	
	<?php endif;?>

	<?php foreach ($pager->links() as $link) : ?>

		<?php if ($link['active']):?>

			<li class="page-item active"><a class="page-link" href="<?= $link['uri'];?>"><?= $link['title'];?> <span class="sr-only">(current)</span></a></li>

		<?php else:?>

			<li class="page-item"><a class="page-link" href="<?= $link['uri'];?>"><?= $link['title'];?></a></li>

		<?php endif;?>
		
	<?php endforeach;?>

	<?php if ($pager->hasNext()) : ?>
	
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getNext();?>" aria-label="<?= lang('base.next');?>">
				<span aria-hidden="true">&raquo;</span>
			</a>
		</li>
	
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getLast();?>" aria-label="<?= lang('base.last');?>">
				<span aria-hidden="true"><?= lang('base.last');?></span>
			</a>
		</li>
	
	<?php endif ?>	

  </ul>

</nav>
