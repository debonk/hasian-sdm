<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-4">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-4">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
          <li><a href="<?php echo $presence_log; ?>"><?php echo $text_presence_log; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-4">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $schedule; ?>"><?php echo $text_schedule; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer>
<script type="text/javascript"><!--
// function downloadJSAtOnload() {
	// var element = document.createElement("script");
	// element.src = "catalog/view/javascript/willow_js.min.js";
	// document.body.appendChild(element);
// }
// if (window.addEventListener)
// window.addEventListener("load", downloadJSAtOnload, false);
// else if (window.attachEvent)
// window.attachEvent("onload", downloadJSAtOnload);
// else window.onload = downloadJSAtOnload;
//--></script>
</body></html>