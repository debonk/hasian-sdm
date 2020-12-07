<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <li id="customer"><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer; ?></span></a>
    <ul>
      <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
      <li><a href="<?php echo $document; ?>"><?php echo $text_document; ?></a></li>
      <li><a href="<?php echo $finger; ?>"><?php echo $text_finger; ?></a></li>
    </ul>
  </li>
  <li id="component"><a class="parent"><i class="fa fa-th-large fa-fw"></i> <span><?php echo $text_component; ?></span></a>
    <ul>
      <li><a href="<?php echo $loan; ?>"><?php echo $text_loan; ?></a></li>
      <li><a href="<?php echo $cutoff; ?>"><?php echo $text_cutoff; ?></a></li>
      <li><a href="<?php echo $incentive; ?>"><?php echo $text_incentive; ?></a></li>
	  <li><a class="parent"><?php echo $text_overtime; ?></a>
		<ul>
		  <li><a href="<?php echo $overtime; ?>"><?php echo $text_overtime; ?></a></li>
		  <li><a href="<?php echo $overtime_type; ?>"><?php echo $text_overtime_type; ?></a></li>
		</ul>
	  </li>
   </ul>
  </li>
  <li id="presence"><a class="parent"><i class="fa fa-calendar fa-fw"></i> <span><?php echo $text_presence; ?></span></a>
    <ul>
      <li><a href="<?php echo $presence_period; ?>"><?php echo $text_presence_period; ?></a></li>
      <li><a href="<?php echo $schedule; ?>"><?php echo $text_schedule; ?></a></li>
      <li><a href="<?php echo $exchange; ?>"><?php echo $text_exchange; ?></a></li>
      <li><a href="<?php echo $absence; ?>"><?php echo $text_absence; ?></a></li>
      <li><a href="<?php echo $presence; ?>"><?php echo $text_presence; ?></a></li>
    </ul>
  </li>
  <li id="payroll"><a class="parent"><i class="fa fa-money fa-fw"></i> <span><?php echo $text_payroll; ?></span></a>
    <ul>
      <li><a href="<?php echo $payroll; ?>"><?php echo $text_payroll; ?></a></li>
      <li><a href="<?php echo $payroll_basic; ?>"><?php echo $text_payroll_basic; ?></a></li>
    </ul>
  </li>
  <li id="release"><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_payroll_release; ?></span></a>
    <ul>
      <li><a href="<?php echo $payroll_release; ?>"><?php echo $text_payroll_release; ?></a></li>
      <li><a href="<?php echo $free_transfer; ?>"><?php echo $text_free_transfer; ?></a></li>
      <li><a href="<?php echo $allowance; ?>"><?php echo $text_allowance; ?></a></li>
    </ul>
  </li>
  <li id="report"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_report; ?></span></a>
    <ul>
      <li><a class="parent"><?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $report_customer; ?>"><?php echo $text_report_customer; ?></a></li>
          <li><a href="<?php echo $report_customer_loan; ?>"><?php echo $text_report_customer_loan; ?></a></li>
          <li><a href="<?php echo $report_customer_history; ?>"><?php echo $text_report_customer_history; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_payroll; ?></a>
        <ul>
          <li><a href="<?php echo $report_payroll_insurance; ?>"><?php echo $text_report_payroll_insurance; ?></a></li>
          <li><a href="<?php echo $report_payroll_tax; ?>"><?php echo $text_report_payroll_tax; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="catalog"><a class="parent"><i class="fa fa-info fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
      <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
    </ul>
  </li>
  <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
    <ul>
      <li><a href="<?php echo $installer; ?>"><?php echo $text_installer; ?></a></li>
      <li><a href="<?php echo $modification; ?>"><?php echo $text_modification; ?></a></li>
      <li><a href="<?php echo $theme; ?>"><?php echo $text_theme; ?></a></li>
      <li><a href="<?php echo $component; ?>"><?php echo $text_component; ?></a></li>
      <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
    </ul>
  </li>

  <!-- pav 2.2 edit -->
  <li><a class="parent">
    <i class="fa fa-rocket fa-fw"></i> <span><?php echo $text_themecontrol; ?></span></a>
    <ul>
      <li><a href="<?php echo $themecontrol; ?>"><?php echo $text_themecontrol; ?></a></li>
      <li><a href="<?php echo $pavmegamenu; ?>"><?php echo $text_pavmegamenu; ?></a></li>
      <li><a href="<?php echo $pavblog; ?>"><?php echo $text_pavblog; ?></a></li>
    </ul> 
  </li>
  <!-- pav 2.2 end edit -->

  <li id="localisation"><a class="parent"><i class="fa fa-globe fa-fw"></i> <span><?php echo $text_localisation; ?></span></a>
    <ul>
      <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
      <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
      <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
      <li><a href="<?php echo $customer_department; ?>"><?php echo $text_customer_department; ?></a></li>
      <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
      <li><a href="<?php echo $gender; ?>"><?php echo $text_gender; ?></a></li>
      <li><a href="<?php echo $marriage_status; ?>"><?php echo $text_marriage_status; ?></a></li>
      <li><a href="<?php echo $payroll_method; ?>"><?php echo $text_payroll_method; ?></a></li>
      <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
      <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
      <li><a href="<?php echo $city; ?>"><?php echo $text_city; ?></a></li>
      <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
      <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
      <li><a href="<?php echo $finger_device; ?>"><?php echo $text_finger_device; ?></a></li>
      <li><a href="<?php echo $document_type; ?>"><?php echo $text_document_type; ?></a></li>
      <li><a href="<?php echo $schedule_type; ?>"><?php echo $text_schedule_type; ?></a></li>
      <li><a href="<?php echo $presence_status; ?>"><?php echo $text_presence_status; ?></a></li>
      <li><a href="<?php echo $fund_account; ?>"><?php echo $text_fund_account; ?></a></li>
      <li><a href="<?php echo $payroll_status; ?>"><?php echo $text_payroll_status; ?></a></li>
</ul>
  </li>
  <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul>
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
      <li><a href="<?php echo $payroll_setting; ?>"><?php echo $text_payroll_setting; ?></a></li>
      <li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_tools; ?></a>
        <ul>
          <li><a href="<?php echo $sysinfo; ?>"><?php echo $text_sysinfo; ?></a></li>
          <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
