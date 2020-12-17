<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <?php if (isset($menu_groups['customer'])) { ?>
  <li id="customer"><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer; ?></span></a>
    <ul>
      <?php foreach($menu_groups['customer'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
      <?php if (isset($menu_groups['localisation_customer'])) { ?>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <?php foreach($menu_groups['localisation_customer'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['component'])) { ?>
  <li id="component"><a class="parent"><i class="fa fa-th-large fa-fw"></i> <span><?php echo $text_component; ?></span></a>
    <ul>
      <?php foreach($menu_groups['component'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
      <?php if (isset($menu_groups['localisation_component'])) { ?>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <?php foreach($menu_groups['localisation_component'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['presence'])) { ?>
  <li id="presence"><a class="parent"><i class="fa fa-calendar fa-fw"></i> <span><?php echo $text_presence; ?></span></a>
    <ul>
      <?php foreach($menu_groups['presence'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
      <?php if (isset($menu_groups['localisation_presence'])) { ?>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <?php foreach($menu_groups['localisation_presence'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['payroll'])) { ?>
    <li id="payroll"><a class="parent"><i class="fa fa-money fa-fw"></i> <span><?php echo $text_payroll; ?></span></a>
      <ul>
      <?php foreach($menu_groups['payroll'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
      <?php if (isset($menu_groups['localisation_payroll'])) { ?>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <?php foreach($menu_groups['localisation_payroll'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['release'])) { ?>
    <li id="release"><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_payroll_release; ?></span></a>
      <ul>
      <?php foreach($menu_groups['release'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
      <?php if (isset($menu_groups['localisation_release'])) { ?>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <?php foreach($menu_groups['localisation_release'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <li id="report"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_report; ?></span></a>
    <ul>
      <?php if (isset($menu_groups['report_customer'])) { ?>
        <li><a class="parent"><?php echo $text_customer; ?></a>
          <ul>
            <?php foreach($menu_groups['report_customer'] as $menu_item) { ?>
              <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (isset($menu_groups['report_payroll'])) { ?>
        <li><a class="parent"><?php echo $text_payroll; ?></a>
          <ul>
            <?php foreach($menu_groups['report_payroll'] as $menu_item) { ?>
              <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
    </ul>
  </li>
  <?php if (isset($menu_groups['catalog'])) { ?>
    <li id="catalog"><a class="parent"><i class="fa fa-info fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
      <ul>
      <?php foreach($menu_groups['catalog'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['extension'])) { ?>
    <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
      <ul>
      <?php foreach($menu_groups['extension'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['themecontrol'])) { ?>
    <li id="themecontrol"><a class="parent"><i class="fa fa-rocket fa-fw"></i> <span><?php echo $text_themecontrol; ?></span></a>
      <ul>
      <?php foreach($menu_groups['themecontrol'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if (isset($menu_groups['system'])) { ?>
    <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
      <ul>
      <?php foreach($menu_groups['system'] as $menu_item) { ?>
      <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
      <?php } ?>
      <?php if (isset($menu_groups['localisation'])) { ?>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <?php foreach($menu_groups['localisation'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if (isset($menu_groups['user'])) { ?>
      <li><a class="parent"><?php echo $text_user; ?></a>
        <ul>
          <?php foreach($menu_groups['user'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if (isset($menu_groups['tool'])) { ?>
      <li><a class="parent"><?php echo $text_tool; ?></a>
        <ul>
          <?php foreach($menu_groups['tool'] as $menu_item) { ?>
            <li><a href="<?php echo $menu_item['url']; ?>" class="<?php echo $menu_item['class']; ?>"><?php echo $menu_item['text']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
</ul>
