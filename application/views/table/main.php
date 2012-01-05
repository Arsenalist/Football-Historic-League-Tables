<p>
Enter a date and pick a season to find out what the league table looked like at on that day.  Follow me on <a href="http://twitter.com/arsenalist">Twitter</a> and join the <a href="http://facebook.com/arsenalistblog">Facebook page</a>.
</p>

Leagues:

<select name="" onchange="return setvars(this.value)">
<option value="">Select a season</option>
<?php foreach ($leagues as $l) { ?>
    <option value="<?echo $l->league?>-<?echo $l->tier?>-<?echo $l->season?>"
        <?php if ($l->league == $league && $l->tier == $tier && $l->season == $season) echo ' selected ';?>
    
    ><?echo $l->getName()?> (<?php echo $l->getSeasonRange()?>)</option>
<?php }?>
</select>

<form method="get">
Enter Date: <input type="text" id="date" name="date" value="<?echo $date?>"/> <input type="submit" value="Go"/>
<input id="league" name="league" value="<?php echo $league?>" type="hidden"/>
<input id="season" name="season" value="<?php echo $season?>" type="hidden"/>
<input id="tier" name="tier" value="<?php echo $tier?>" type="hidden"/>

</form>

<table>
<tr><th>Team</th><th>P</th><th>W</th><th>D</th><th>L</th><th>GD</th><th>Points</th></tr>
<?php foreach ($teams as $t) { ?>
<tr>
<td><?php echo $t->name?></td>
<td><?php echo $t->getPlayed()?></td>
<td><?php echo $t->wins?></td>
<td><?php echo $t->draws?></td>
<td><?php echo $t->losses?></td>
<td><?php echo $t->getGoalDifference()?></td>
<td><?php echo $t->getPoints()?></td>
</tr>

<?php } ?>
</table>
<br/>
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fstats.arsenalist.com%2Ftable%2F&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>

<script>
setvars = function(str) {
    var arr = str.split('-');
    $('#league').val(arr[0]);
    $('#tier').val(arr[1]);
    $('#season').val(arr[2]);
    var v = $('#date').val();
    if (v != '') {
        $('#date').val((parseInt(arr[2]) + 1) + v.substring(4));
    }
    return false;
}


	$(function() {
		$( "#date" ).datepicker({'dateFormat': 'yy-mm-dd', 'changeYear': true});
	});
	</script>
