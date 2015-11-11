<?php

/*
- Tác giả: Jkey C Phong
- Verison: 2.0
- Facebook: https://www.facebook.com/profile.php?id=100004989074517
- Code grab link Zing TV
- VUI LÒNG GIỮ LẠI HEADER NÀY
*/

Class TvZing
{
	Private Static Function __Curl($Url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		$Headers   = array();
		$Headers[] = 'Host: tv.zing.vn';
		$Headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0';
		$Headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$Headers[] = 'Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3';
		$Headers[] = 'Accept-Encoding: gzip, deflate';
		$Headers[] = 'DNT: 1';
		$Headers[] = 'Connection: keep-alive';
		$Headers[] = 'Cache-Control: max-age=0';
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$Data = curl_exec($ch);
		if (!$Data) {
			die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		}
		$HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return ($HttpCode >= 200 && $HttpCode < 300) ? $Data : 'Lỗi trong quá trình lấy dữ liệu';
	}

	Public Function __Zing($Url)
	{
		$Check = preg_match('#http://tv.zing.vn/series/(.*)#', $Url);
		if ($Check == True) {
			$Source = TvZing::__Curl(preg_replace('#\?p=([0-9]+)#', '', $Url) . '?p=?');
		}
		else {
			$Source = TvZing::__Curl($Url);
			preg_match('#/(.*?)">Xem tất cả#', $Source, $Link_Series);
			$Source = TvZing::__Curl('http://tv.zing.vn/' . $Link_Series[1]);
		}
		preg_match_all('#<a href="/video/(.*?).html">Tập (.*)</a>#', $Source, $Match);
		unset($Source, $Link_Series, $Match[0]);
		$Reverse = array_reverse($Match[1]);
		foreach ($Reverse as $Data) {
			Echo 'http://tv.zing.vn/video/' . $Data . '.html <br />';
		}
	}
}

$Object = new TvZing();
$Object->__Zing('http://tv.zing.vn/shingeki-kyojin-chuugakkou');
?>
