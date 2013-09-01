<div class="content">
 <p class="<?=($this->entry['isBreaking']=='true') ? 'breaking' : ''?>">
  <strong><?=$this->entry['prompt']?></strong>:
  <?php if (isset($this->entry['url'])) { ?>
  <a href="<?=$this->entry['url']?>"><?=$this->entry['headline']?></a>
  <?php } else { ?>
  <?=$this->entry['headline']?>
  <?php } ?>
 </p>
</div>
<ul class="pages"><?=$this->paginator->render()?></ul>
