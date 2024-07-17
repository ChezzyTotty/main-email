<?php
$showCookieNotice = !isset($_COOKIE["cookieConsent"]);
if ($showCookieNotice) {
?>
	<div id="cookie-notice">
		<p>Bu web sitesi, çerezler kullanmaktadır. Çerezleri kullanım amacımız hakkında daha fazla bilgi edinmek için <a href="content/gizlilik-sartlari.html"  target="_blank";>gizlilik politikamızı</a> inceleyebilirsiniz.</p>
		<button id="accept-cookies">Kabul Et</button>
	</div>
	<style>
		#cookie-notice {
			position: fixed;
			top: 50%;
			left: 50%;
            color:#000;
			transform: translate(-50%, -50%);
			width: calc(90% - 200px); /* Genişlik */
			max-width: 600px; /* Maksimum genişlik */
            height:200px;
			background-color: #f2f2f2;
			padding: 20px; /* Padding artırıldı */
			text-align: center;
			z-index: 99999; /* z-index eklendi */
			box-shadow: 0 0 10px rgba(0,0,0,0.5); /* Gölge eklendi */
		}
		#accept-cookies {
		
			background-color: #000;
			padding: 20px 20px; /* Yükseklik ve genişlik */
			border: none;
			bottom:10px;
			border-radius: 51px;
			cursor: pointer;
			float: right; /* Sağ tarafa hareketlendirildi */
		}
		#accept-cookies:hover {
			background-color: #333; /* Hover rengi */
		}
	</style>
	<script>
		var acceptBtn = document.getElementById("accept-cookies");
		var cookieNotice = document.getElementById("cookie-notice");

		// Kabul et butonuna tıklandığında çerezleri kabul et ve sayfayı yenile
		acceptBtn.onclick = function() {
			document.cookie = "cookieConsent=true; path=/; max-age=" + 60*60*24*365;
			location.reload();
		};
	</script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    var menu = document.getElementById("menu");
    
    // Çerez kabul edildiyse menüyü göster
    if (document.cookie.indexOf("cookieConsent=true") !== -1) {
        menu.style.display = "block";
    } else {
        menu.style.display = "none";
    }
});
</script>

<?php
}
?>