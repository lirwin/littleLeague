<h2>Search Members</h2>
<form action="" method="get">

  <div class="search">
    <label for="firstName">By first name:</label>
    <select name="firstName" id="firstName">
      <option value="">Any first name</option>
      <?php foreach ($firstNames as $firstName): ?>
        <option value="<?php htmlout($firstName['first_name']); ?>"><?php
            htmlout($firstName['first_name']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="search">
    <label for="lastName">By last name:</label>
    <select name="lastName" id="lastName">
      <option value="">Any last name</option>
      <?php foreach ($lastNames as $lastName): ?>
        <option value="<?php htmlout($lastName['last_name']); ?>"><?php
            htmlout($lastName['last_name']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="pull-left">
    <label for="message">Message Containing text:</label>
    <input type="text" name="message" id="message">
  </div>
  <div class="search">
    <input type="hidden" name="action" value="search">
    <input type="hidden" name="sort" value="<?php echo $sort ?>">
    <button class="btn btn-primary" type="submit">Search</button>
  </div>
  <div class="clearfix"></div>
</form>