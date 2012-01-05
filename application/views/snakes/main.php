<p>
Select a league to see the title race in graph form.  Follow me on <a href="http://twitter.com/arsenalist">Twitter</a> and join the <a href="http://facebook.com/arsenalistblog">Facebook page</a>.
</p>


<?php 
$code = $this->input->get('code') == null ? 'eng12010' : $this->input->get('code');

?>
<form id="form">
<select name="code" onchange="$('#form').submit()">
<?php foreach ($leagues as $l) { ?>
    <option value="<?echo $l->league?><?echo $l->tier?><?echo $l->season?>"
        <?php if ($l->league . $l->tier . $l->season == $code) echo ' selected ';?>    
    ><?echo $l->getName()?> (<?php echo $l->getSeasonRange()?>)</option>
<?php }?>
</select>
</form>
<img src="/snakes/image?code=<?php echo $code?>"/>

<br/>
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fstats.arsenalist.com%2Fsnakes%2F&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>
