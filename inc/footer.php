			</tr>
          </tbody>
        </table>
		<div class="clear"></div>
			<footer class="round">
			<ul>
			<li>&copy; 2010-date("Y") Contributors incl. <a href="profile.php" title="It is you that make it!" target="_blank"><?php echo getEncryptCookie('twitese_name')?></a></li>
			<?php if (BLOG_SITE) { ?><li><a href="<?php echo BLOG_SITE ?>" title="Site Owner's Blog" target="_blank">Blog</a></li><?php }?>
			<li><a href="http://code.google.com/p/tuite/" target="_blank" title="Embr is proundly powered by the Open Source project - Twitese & Rabr">Twitese</a></li>
			<li><a href="https://github.com/AstroProfundis/embrr" target="_blank">Open Source</a></li>
			<?php if (SITE_OWNER) { ?><li>Run by <a href="https://twitter.com/<?php echo SITE_OWNER ?>" target="_blank"><?php echo SITE_OWNER ?></a></li><?php }?>
			</ul>
			</footer>
		</div>
	</div>
<script>var nav=document.getElementById("primary_nav");var links=nav.getElementsByTagName("a");var currenturl=document.location.href;for(var i=0;i<links.length;i++){var linkurl=links[i].getAttribute("href");if(currenturl==links[i]){links[i].className="active";}}</script>
</body>
</html>
<?php ob_end_flush(); ?>
