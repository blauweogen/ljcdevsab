<?php
echo "
<p>CSV is a tabular format that consists of rows and columns. Each row in
a CSV file represents an event post; each column identifies a piece of information
that comprises an event data. It is <b>VERY Important</b> to built your CSV file correctly to successfully import events.</p>

		<h4>Acceptable CSV file fields</h4>
		<div style='padding-left:20px'>
			<p><b>publish_status</b> - Publish status of the event: <i class='highl'>publish | draft</i></p>
			<p><b>featured</b> - Feature an event or no eg <b class='highl'>yes | no</b></p>
			<p><b>color</b> - Hex color for the event without # sign</p>
			<p><b>event_name</b> - Name of the event</p>
			<p><b>event_description</b> - Event main description</p>
			<p><b>event_start_date & event_end_date</b> - Start/End date in format <i class='highl'>mm/dd/YYYY</i></p>
			<p><b>event_start_time & event_end_time</b> - Start/End time in format <i class='highl'>h:mm:AM/PM</i></p>
			<p><b>location_name & event_location</b> - Event Location name and address</p>
			<p><b>event_gmap</b> - Generate google maps from address. eg <b class='highl'>yes | no</b></p>
			<p><b>event_organizer</b> - Event Organizer</p>
			<p><b>all_day</b> - All day event eg. <b class='highl'>yes | no</b></p>
			<p><b>hide end time</b> -Hide end time for event eg. <b class='highl'>yes | no</b></p>
			<p><b>event_type categories</b> - Event type category term IDs seperated by commas eg. 4,19. You can add upto 5 event type categories in seperate columns with column headers in format <b class='highl'>event_type_{x}</b></p>
			<p><b>cmd_{x} </b> - Custom meta data values. You can add upto 10 custom meta field values with sepeate columns with column headers in <b class='highl'>format cmd_{x}</b></p>
		</div>
		<br/>
		<p><i><b>NOTE: BOTH Start time and end times are required for the time to be stored.</b><br/>Please check the \"sample.csv\" file that came with the addon for more instruction guidance.</i></p>

<p><b>Requirements:</b> EventON version 2.2.12 or higher</p>
";
?>