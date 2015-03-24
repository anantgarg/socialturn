	<div class="row">
		<div class="col-xs-12">
			<h4>Schedule</h4>
			<p>So, when would you like your posts to be sent? Choose your timezone, schedule your times and we'll make sure your posts are sent out even when you're asleep!</p>
		</div>
	</div>

	<form action="<?php echo BASE_URL;?>social/schedule-update/<?php echo $current;?>" method="post" enctype="multipart/form-data">
		
		<div class="row spacer-sm">
			<div class="col-xs-12">

			<p>Select your timezone (can be different from your current location)</p>
				<select class="selectpicker" name="timezone" id="timezone">
      <option <?php if ($timezone == "-12.0"):?>selected<?php endif;?> value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
      <option <?php if ($timezone == "-11.0"):?>selected<?php endif;?> value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
      <option <?php if ($timezone == "-10.0"):?>selected<?php endif;?> value="-10.0">(GMT -10:00) Hawaii</option>
      <option <?php if ($timezone == "-9.0"):?>selected<?php endif;?> value="-9.0">(GMT -9:00) Alaska</option>
      <option <?php if ($timezone == "-8.0"):?>selected<?php endif;?> value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
      <option <?php if ($timezone == "-7.0"):?>selected<?php endif;?> value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option <?php if ($timezone == "-6.0"):?>selected<?php endif;?> value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
      <option <?php if ($timezone == "-5.0"):?>selected<?php endif;?> value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option <?php if ($timezone == "-4.0"):?>selected<?php endif;?> value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option <?php if ($timezone == "-3.5"):?>selected<?php endif;?> value="-3.5">(GMT -3:30) Newfoundland</option>
      <option <?php if ($timezone == "-3.0"):?>selected<?php endif;?> value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
      <option <?php if ($timezone == "-2.0"):?>selected<?php endif;?> value="-2.0">(GMT -2:00) Mid-Atlantic</option>
      <option <?php if ($timezone == "-1.0"):?>selected<?php endif;?> value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option <?php if ($timezone == "0.0"):?>selected<?php endif;?> value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
      <option <?php if ($timezone == "1.0"):?>selected<?php endif;?> value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
      <option <?php if ($timezone == "2.0"):?>selected<?php endif;?> value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
      <option <?php if ($timezone == "3.0"):?>selected<?php endif;?> value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
      <option <?php if ($timezone == "3.5"):?>selected<?php endif;?> value="3.5">(GMT +3:30) Tehran</option>
      <option <?php if ($timezone == "4.0"):?>selected<?php endif;?> value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option <?php if ($timezone == "4.5"):?>selected<?php endif;?> value="4.5">(GMT +4:30) Kabul</option>
      <option <?php if ($timezone == "5.0"):?>selected<?php endif;?> value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option <?php if ($timezone == "5.5"):?>selected<?php endif;?> value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option <?php if ($timezone == "5.75"):?>selected<?php endif;?> value="5.75">(GMT +5:45) Kathmandu</option>
      <option <?php if ($timezone == "6.0"):?>selected<?php endif;?> value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
      <option <?php if ($timezone == "7.0"):?>selected<?php endif;?> value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option <?php if ($timezone == "8.0"):?>selected<?php endif;?> value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
      <option <?php if ($timezone == "9.0"):?>selected<?php endif;?> value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option <?php if ($timezone == "9.5"):?>selected<?php endif;?> value="9.5">(GMT +9:30) Adelaide, Darwin</option>
      <option <?php if ($timezone == "10.0"):?>selected<?php endif;?> value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option <?php if ($timezone == "11.0"):?>selected<?php endif;?> value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option <?php if ($timezone == "12.0"):?>selected<?php endif;?> value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
</select>

			</div>
		</div>

		<div class="row spacer-sm">
			<div class="col-xs-12">
				<p>Which days would you like to post?</p>
				<select class="selectpicker" name="days[]" multiple title='No days selected' data-selected-text-format="count>3">
				  <option <?php if (!empty($days["1"])):?>selected<?php endif;?> value="1">Monday</option>
				  <option <?php if (!empty($days["2"])):?>selected<?php endif;?> value="2">Tuesday</option>
				  <option <?php if (!empty($days["3"])):?>selected<?php endif;?> value="3">Wednesday</option>
				  <option <?php if (!empty($days["4"])):?>selected<?php endif;?> value="4">Thursday</option>
				  <option <?php if (!empty($days["5"])):?>selected<?php endif;?> value="5">Friday</option>
				  <option <?php if (!empty($days["6"])):?>selected<?php endif;?> value="6">Saturday</option>
				  <option <?php if (!empty($days["7"])):?>selected<?php endif;?> value="7">Sunday</option>
	 		    </select>

			</div>
		</div>
		
		<div class="row spacer-sm">
			<div class="col-xs-12">
				<p>What time would you like to post each selected day?</p>
				<select class="selectpicker" name="time[]" multiple title='No time options selected' data-selected-text-format="count>3">
				 <option <?php if (!empty($times["0000"])):?>selected<?php endif;?> value="0000">12am</option>
				 <option <?php if (!empty($times["0100"])):?>selected<?php endif;?>  value="0100">1am</option>
				 <option <?php if (!empty($times["0200"])):?>selected<?php endif;?>  value="0200">2am</option>
				 <option <?php if (!empty($times["0300"])):?>selected<?php endif;?>  value="0300">3am</option>
				 <option <?php if (!empty($times["0400"])):?>selected<?php endif;?>  value="0400">4am</option>
				 <option <?php if (!empty($times["0500"])):?>selected<?php endif;?>  value="0500">5am</option>
				 <option <?php if (!empty($times["0600"])):?>selected<?php endif;?>  value="0600">6am</option>
				 <option <?php if (!empty($times["0700"])):?>selected<?php endif;?>  value="0700">7am</option>
				 <option <?php if (!empty($times["0800"])):?>selected<?php endif;?>  value="0800">8am</option>
				 <option <?php if (!empty($times["0900"])):?>selected<?php endif;?>  value="0900">9am</option>
				 <option <?php if (!empty($times["1000"])):?>selected<?php endif;?>  value="1000">10am</option>
				 <option <?php if (!empty($times["1100"])):?>selected<?php endif;?>  value="1100">11am</option>
				 <option <?php if (!empty($times["1200"])):?>selected<?php endif;?>  value="1200">12pm</option>
				 <option <?php if (!empty($times["1300"])):?>selected<?php endif;?>  value="1300">1pm</option>
				 <option <?php if (!empty($times["1400"])):?>selected<?php endif;?>  value="1400">2pm</option>
				 <option <?php if (!empty($times["1500"])):?>selected<?php endif;?>  value="1500">3pm</option>
				 <option <?php if (!empty($times["1600"])):?>selected<?php endif;?>  value="1600">4pm</option>
				 <option <?php if (!empty($times["1700"])):?>selected<?php endif;?>  value="1700">5pm</option>
				 <option <?php if (!empty($times["1800"])):?>selected<?php endif;?>  value="1800">6pm</option>
				 <option <?php if (!empty($times["1900"])):?>selected<?php endif;?>  value="1900">7pm</option>
				 <option <?php if (!empty($times["2000"])):?>selected<?php endif;?>  value="2000">8pm</option>
				 <option <?php if (!empty($times["2100"])):?>selected<?php endif;?>  value="2100">9pm</option>
				 <option <?php if (!empty($times["2200"])):?>selected<?php endif;?>  value="2200">10pm</option>
				 <option <?php if (!empty($times["2300"])):?>selected<?php endif;?>  value="2300">11pm</option>
	 		    </select>

			</div>
		</div>
		<div class="row spacer-sm">
			<div class="col-xs-12">
				<button type="submit" class="btn btn-primary" onclick="$(this).text('Saving...')">Save Schedule</button>
			</div>
		</div>

	</form>	
