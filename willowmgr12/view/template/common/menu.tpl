<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <?php foreach($menu_groups as $group_code => $menu_group) { ?>
    <?php if (isset($menu_group)) { ?>
      <li id="<?php echo $menu_titles[$group_code]['text']; ?>"><a class="parent"><i class="fa <?php echo $menu_titles[$group_code]['icon']; ?> fa-fw"></i> <span><?php echo $menu_titles[$group_code]['text']; ?></span></a>
        <ul>
          <?php foreach($menu_group as $code => $menu_item) { ?>
            <?php if (isset($child_groups[$group_code][$code])) { ?>
              <li><a class="parent"><?php echo $menu_item['text']; ?></a>
                <ul>
                  <?php foreach($child_groups[$group_code][$code] as $child_item) { ?>
                    <li><a href="<?php echo $child_item['url']; ?>" class="<?php echo $child_item['class']; ?>"><?php echo $child_item['text']; ?></a></li>
                  <?php } ?>
                </ul>
              </li>
            <?php } else { ?>
              <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
            <?php } ?>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
  <?php } ?>
</ul>
