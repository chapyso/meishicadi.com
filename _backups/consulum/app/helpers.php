<?php
    function pixelSourceCode($platform, $pixelId)
    {
    	// Facebook Pixel script
    	if ($platform === 'facebook') {
			$script = "
				<script>
					!function(f,b,e,v,n,t,s)
					{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
					n.callMethod.apply(n,arguments):n.queue.push(arguments)};
					if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
					n.queue=[];t=b.createElement(e);t.async=!0;
					t.src=v;s=b.getElementsByTagName(e)[0];
					s.parentNode.insertBefore(t,s)}(window, document,'script',
					'https://connect.facebook.net/en_US/fbevents.js');
					fbq('init', '%s');
					fbq('track', 'PageView');
				</script>

				<noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=%d&ev=PageView&noscript=1'/></noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Twitter Pixel script
    	if ($platform === 'twitter') {
			$script = "
            <script>
            !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
            },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
            a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
            twq('config','%s');
            </script>
			";

			return sprintf($script, $pixelId);
		}


		// Linkedin Pixel script
    	if ($platform === 'linkedin') {
			$script = "
				<script type='text/javascript'>
                    _linkedin_data_partner_id = %d;
                </script>
                <script type='text/javascript'>
                    (function () {
                        var s = document.getElementsByTagName('script')[0];
                        var b = document.createElement('script');
                        b.type = 'text/javascript';
                        b.async = true;
                        b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
                        s.parentNode.insertBefore(b, s);
                    })();
                </script>
                <noscript><img height='1' width='1' style='display:none;' alt='' src='https://dc.ads.linkedin.com/collect/?pid=%d&fmt=gif'/></noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Pinterest Pixel script
    	if ($platform === 'pinterest') {
			$script = "
            <!-- Pinterest Tag -->
            <script>
            !function(e){if(!window.pintrk){window.pintrk = function () {
            window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
              n=window.pintrk;n.queue=[],n.version='3.0';var
              t=document.createElement('script');t.async=!0,t.src=e;var
              r=document.getElementsByTagName('script')[0];
              r.parentNode.insertBefore(t,r)}}('https://s.pinimg.com/ct/core.js');
            pintrk('load', '%s');
            pintrk('page');
            </script>
            <noscript>
            <img height='1' width='1' style='display:none;' alt=''
              src='https://ct.pinterest.com/v3/?event=init&tid=2613174167631&pd[em]=<hashed_email_address>&noscript=1' />
            </noscript>
            <!-- end Pinterest Tag -->

			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Quora Pixel script
    	if ($platform === 'quora') {
			$script = "
               <script>
                    !function (q, e, v, n, t, s) {
                        if (q.qp) return;
                        n = q.qp = function () {
                            n.qp ? n.qp.apply(n, arguments) : n.queue.push(arguments);
                        };
                        n.queue = [];
                        t = document.createElement(e);
                        t.async = !0;
                        t.src = v;
                        s = document.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t, s);
                    }(window, 'script', 'https://a.quora.com/qevents.js');
                    qp('init', %s);
                    qp('track', 'ViewContent');
                </script>

                <noscript><img height='1' width='1' style='display:none' src='https://q.quora.com/_/ad/%d/pixel?tag=ViewContent&noscript=1'/></noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}



		// Bing Pixel script
    	if ($platform === 'bing') {
			$script = '
				<script>
				(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[] ,f=function(){var o={ti:"%d"}; o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")} ,n=d.createElement(t),n.src=r,n.async=1,n.onload=n .onreadystatechange=function() {var s=this.readyState;s &&s!=="loaded"&& s!=="complete"||(f(),n.onload=n. onreadystatechange=null)},i= d.getElementsByTagName(t)[0],i. parentNode.insertBefore(n,i)})(window,document,"script"," //bat.bing.com/bat.js","uetq");
				</script>
				<noscript><img src="//bat.bing.com/action/0?ti=%d&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
			';

			return sprintf($script, $pixelId, $pixelId);
		}



		// Google adwords Pixel script
    	if ($platform === 'google-adwords') {
			$script = "
				<script type='text/javascript'>

				var google_conversion_id = '%s';
				var google_custom_params = window.google_tag_params;
				var google_remarketing_only = true;

				</script>
				<script type='text/javascript' src='//www.googleadservices.com/pagead/conversion.js'>
				</script>
				<noscript>
				<div style='display:inline;'>
				<img height='1' width='1' style='border-style:none;' alt='' src='//googleads.g.doubleclick.net/pagead/viewthroughconversion/%s/?guid=ON&amp;script=0'/>
				</div>
				</noscript>
			";

			return sprintf($script, $pixelId, $pixelId);
		}


		// Google tag manager Pixel script
    	if ($platform === 'google-analytics') {
			$script = "
				<script async src='https://www.googletagmanager.com/gtag/js?id=%s'></script>
				<script>

				  window.dataLayer = window.dataLayer || [];

				  function gtag(){dataLayer.push(arguments);}

				  gtag('js', new Date());

				  gtag('config', '%s');

				</script>
			";

			return sprintf($script, $pixelId, $pixelId);
		}

        //snapchat
        if ($platform === 'snapchat') {
			$script = " <script type='text/javascript'>
            (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
            {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
            a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
            r.src=n;var u=t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r,u);})(window,document,
            'https://sc-static.net/scevent.min.js');

            snaptr('init', '%s', {
            'user_email': '__INSERT_USER_EMAIL__'
            });

            snaptr('track', 'PAGE_VIEW');

            </script>";
			return sprintf($script, $pixelId, $pixelId);
		}

        //tiktok
        if ($platform === 'tiktok') {
			$script = " <script>
            !function (w, d, t) {
              w.TiktokAnalyticsObject=t;
              var ttq=w[t]=w[t]||[];
              ttq.methods=['page','track','identify','instances','debug','on','off','once','ready','alias','group','enableCookie','disableCookie'],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
              for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;
             n++)ttq.setAndDefer(e,ttq.methods[n]);
             return e},ttq.load=function(e,n){var i='https://analytics.tiktok.com/i18n/pixel/events.js';
            ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};
            var o=document.createElement('script');
            o.type='text/javascript',o.async=!0,o.src=i+'?sdkid='+e+'&lib='+t;
            var a=document.getElementsByTagName('script')[0];
            a.parentNode.insertBefore(o,a)};

              ttq.load('%s');
              ttq.page();
            }(window, document, 'ttq');
            </script>";

			return sprintf($script, $pixelId, $pixelId);
		}




    }

	if(! function_exists('get_device_type')){
		function get_device_type($user_agent)
		{
				$mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
				$tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
	
				if(preg_match_all($mobile_regex, $user_agent)) {
					return 'mobile';
				} else {
	
					if(preg_match_all($tablet_regex, $user_agent)) {
						return 'tablet';
					} else {
						return 'desktop';
					}
	
				}
		}
	}

	if(! function_exists('get_country_from_phone')){
		function get_country_from_phone($phone)
		{
			if (empty($phone)) {
				return ['country' => 'Unknown', 'code' => '', 'country_code' => ''];
			}

			// Remove all non-numeric characters except + at the start
			$phone = preg_replace('/[^\d+]/', '', $phone);
			
			// Country code to country name mapping (most common codes)
			$countryCodes = [
				'1' => ['country' => 'United States', 'code' => 'US', 'country_code' => '+1'],
				'7' => ['country' => 'Russia', 'code' => 'RU', 'country_code' => '+7'],
				'20' => ['country' => 'Egypt', 'code' => 'EG', 'country_code' => '+20'],
				'27' => ['country' => 'South Africa', 'code' => 'ZA', 'country_code' => '+27'],
				'30' => ['country' => 'Greece', 'code' => 'GR', 'country_code' => '+30'],
				'31' => ['country' => 'Netherlands', 'code' => 'NL', 'country_code' => '+31'],
				'32' => ['country' => 'Belgium', 'code' => 'BE', 'country_code' => '+32'],
				'33' => ['country' => 'France', 'code' => 'FR', 'country_code' => '+33'],
				'34' => ['country' => 'Spain', 'code' => 'ES', 'country_code' => '+34'],
				'36' => ['country' => 'Hungary', 'code' => 'HU', 'country_code' => '+36'],
				'39' => ['country' => 'Italy', 'code' => 'IT', 'country_code' => '+39'],
				'40' => ['country' => 'Romania', 'code' => 'RO', 'country_code' => '+40'],
				'41' => ['country' => 'Switzerland', 'code' => 'CH', 'country_code' => '+41'],
				'43' => ['country' => 'Austria', 'code' => 'AT', 'country_code' => '+43'],
				'44' => ['country' => 'United Kingdom', 'code' => 'GB', 'country_code' => '+44'],
				'45' => ['country' => 'Denmark', 'code' => 'DK', 'country_code' => '+45'],
				'46' => ['country' => 'Sweden', 'code' => 'SE', 'country_code' => '+46'],
				'47' => ['country' => 'Norway', 'code' => 'NO', 'country_code' => '+47'],
				'48' => ['country' => 'Poland', 'code' => 'PL', 'country_code' => '+48'],
				'49' => ['country' => 'Germany', 'code' => 'DE', 'country_code' => '+49'],
				'51' => ['country' => 'Peru', 'code' => 'PE', 'country_code' => '+51'],
				'52' => ['country' => 'Mexico', 'code' => 'MX', 'country_code' => '+52'],
				'53' => ['country' => 'Cuba', 'code' => 'CU', 'country_code' => '+53'],
				'54' => ['country' => 'Argentina', 'code' => 'AR', 'country_code' => '+54'],
				'55' => ['country' => 'Brazil', 'code' => 'BR', 'country_code' => '+55'],
				'56' => ['country' => 'Chile', 'code' => 'CL', 'country_code' => '+56'],
				'57' => ['country' => 'Colombia', 'code' => 'CO', 'country_code' => '+57'],
				'58' => ['country' => 'Venezuela', 'code' => 'VE', 'country_code' => '+58'],
				'60' => ['country' => 'Malaysia', 'code' => 'MY', 'country_code' => '+60'],
				'61' => ['country' => 'Australia', 'code' => 'AU', 'country_code' => '+61'],
				'62' => ['country' => 'Indonesia', 'code' => 'ID', 'country_code' => '+62'],
				'63' => ['country' => 'Philippines', 'code' => 'PH', 'country_code' => '+63'],
				'64' => ['country' => 'New Zealand', 'code' => 'NZ', 'country_code' => '+64'],
				'65' => ['country' => 'Singapore', 'code' => 'SG', 'country_code' => '+65'],
				'66' => ['country' => 'Thailand', 'code' => 'TH', 'country_code' => '+66'],
				'81' => ['country' => 'Japan', 'code' => 'JP', 'country_code' => '+81'],
				'82' => ['country' => 'South Korea', 'code' => 'KR', 'country_code' => '+82'],
				'84' => ['country' => 'Vietnam', 'code' => 'VN', 'country_code' => '+84'],
				'86' => ['country' => 'China', 'code' => 'CN', 'country_code' => '+86'],
				'90' => ['country' => 'Turkey', 'code' => 'TR', 'country_code' => '+90'],
				'91' => ['country' => 'India', 'code' => 'IN', 'country_code' => '+91'],
				'92' => ['country' => 'Pakistan', 'code' => 'PK', 'country_code' => '+92'],
				'93' => ['country' => 'Afghanistan', 'code' => 'AF', 'country_code' => '+93'],
				'94' => ['country' => 'Sri Lanka', 'code' => 'LK', 'country_code' => '+94'],
				'95' => ['country' => 'Myanmar', 'code' => 'MM', 'country_code' => '+95'],
				'98' => ['country' => 'Iran', 'code' => 'IR', 'country_code' => '+98'],
				'212' => ['country' => 'Morocco', 'code' => 'MA', 'country_code' => '+212'],
				'213' => ['country' => 'Algeria', 'code' => 'DZ', 'country_code' => '+213'],
				'216' => ['country' => 'Tunisia', 'code' => 'TN', 'country_code' => '+216'],
				'218' => ['country' => 'Libya', 'code' => 'LY', 'country_code' => '+218'],
				'220' => ['country' => 'Gambia', 'code' => 'GM', 'country_code' => '+220'],
				'221' => ['country' => 'Senegal', 'code' => 'SN', 'country_code' => '+221'],
				'222' => ['country' => 'Mauritania', 'code' => 'MR', 'country_code' => '+222'],
				'223' => ['country' => 'Mali', 'code' => 'ML', 'country_code' => '+223'],
				'224' => ['country' => 'Guinea', 'code' => 'GN', 'country_code' => '+224'],
				'225' => ['country' => 'Ivory Coast', 'code' => 'CI', 'country_code' => '+225'],
				'226' => ['country' => 'Burkina Faso', 'code' => 'BF', 'country_code' => '+226'],
				'227' => ['country' => 'Niger', 'code' => 'NE', 'country_code' => '+227'],
				'228' => ['country' => 'Togo', 'code' => 'TG', 'country_code' => '+228'],
				'229' => ['country' => 'Benin', 'code' => 'BJ', 'country_code' => '+229'],
				'230' => ['country' => 'Mauritius', 'code' => 'MU', 'country_code' => '+230'],
				'231' => ['country' => 'Liberia', 'code' => 'LR', 'country_code' => '+231'],
				'232' => ['country' => 'Sierra Leone', 'code' => 'SL', 'country_code' => '+232'],
				'233' => ['country' => 'Ghana', 'code' => 'GH', 'country_code' => '+233'],
				'234' => ['country' => 'Nigeria', 'code' => 'NG', 'country_code' => '+234'],
				'235' => ['country' => 'Chad', 'code' => 'TD', 'country_code' => '+235'],
				'236' => ['country' => 'Central African Republic', 'code' => 'CF', 'country_code' => '+236'],
				'237' => ['country' => 'Cameroon', 'code' => 'CM', 'country_code' => '+237'],
				'238' => ['country' => 'Cape Verde', 'code' => 'CV', 'country_code' => '+238'],
				'239' => ['country' => 'São Tomé and Príncipe', 'code' => 'ST', 'country_code' => '+239'],
				'240' => ['country' => 'Equatorial Guinea', 'code' => 'GQ', 'country_code' => '+240'],
				'241' => ['country' => 'Gabon', 'code' => 'GA', 'country_code' => '+241'],
				'242' => ['country' => 'Republic of the Congo', 'code' => 'CG', 'country_code' => '+242'],
				'243' => ['country' => 'DR Congo', 'code' => 'CD', 'country_code' => '+243'],
				'244' => ['country' => 'Angola', 'code' => 'AO', 'country_code' => '+244'],
				'245' => ['country' => 'Guinea-Bissau', 'code' => 'GW', 'country_code' => '+245'],
				'246' => ['country' => 'British Indian Ocean Territory', 'code' => 'IO', 'country_code' => '+246'],
				'248' => ['country' => 'Seychelles', 'code' => 'SC', 'country_code' => '+248'],
				'249' => ['country' => 'Sudan', 'code' => 'SD', 'country_code' => '+249'],
				'250' => ['country' => 'Rwanda', 'code' => 'RW', 'country_code' => '+250'],
				'251' => ['country' => 'Ethiopia', 'code' => 'ET', 'country_code' => '+251'],
				'252' => ['country' => 'Somalia', 'code' => 'SO', 'country_code' => '+252'],
				'253' => ['country' => 'Djibouti', 'code' => 'DJ', 'country_code' => '+253'],
				'254' => ['country' => 'Kenya', 'code' => 'KE', 'country_code' => '+254'],
				'255' => ['country' => 'Tanzania', 'code' => 'TZ', 'country_code' => '+255'],
				'256' => ['country' => 'Uganda', 'code' => 'UG', 'country_code' => '+256'],
				'257' => ['country' => 'Burundi', 'code' => 'BI', 'country_code' => '+257'],
				'258' => ['country' => 'Mozambique', 'code' => 'MZ', 'country_code' => '+258'],
				'260' => ['country' => 'Zambia', 'code' => 'ZM', 'country_code' => '+260'],
				'261' => ['country' => 'Madagascar', 'code' => 'MG', 'country_code' => '+261'],
				'262' => ['country' => 'Réunion', 'code' => 'RE', 'country_code' => '+262'],
				'263' => ['country' => 'Zimbabwe', 'code' => 'ZW', 'country_code' => '+263'],
				'264' => ['country' => 'Namibia', 'code' => 'NA', 'country_code' => '+264'],
				'265' => ['country' => 'Malawi', 'code' => 'MW', 'country_code' => '+265'],
				'266' => ['country' => 'Lesotho', 'code' => 'LS', 'country_code' => '+266'],
				'267' => ['country' => 'Botswana', 'code' => 'BW', 'country_code' => '+267'],
				'268' => ['country' => 'Eswatini', 'code' => 'SZ', 'country_code' => '+268'],
				'269' => ['country' => 'Comoros', 'code' => 'KM', 'country_code' => '+269'],
				'290' => ['country' => 'Saint Helena', 'code' => 'SH', 'country_code' => '+290'],
				'291' => ['country' => 'Eritrea', 'code' => 'ER', 'country_code' => '+291'],
				'297' => ['country' => 'Aruba', 'code' => 'AW', 'country_code' => '+297'],
				'298' => ['country' => 'Faroe Islands', 'code' => 'FO', 'country_code' => '+298'],
				'299' => ['country' => 'Greenland', 'code' => 'GL', 'country_code' => '+299'],
				'350' => ['country' => 'Gibraltar', 'code' => 'GI', 'country_code' => '+350'],
				'351' => ['country' => 'Portugal', 'code' => 'PT', 'country_code' => '+351'],
				'352' => ['country' => 'Luxembourg', 'code' => 'LU', 'country_code' => '+352'],
				'353' => ['country' => 'Ireland', 'code' => 'IE', 'country_code' => '+353'],
				'354' => ['country' => 'Iceland', 'code' => 'IS', 'country_code' => '+354'],
				'355' => ['country' => 'Albania', 'code' => 'AL', 'country_code' => '+355'],
				'356' => ['country' => 'Malta', 'code' => 'MT', 'country_code' => '+356'],
				'357' => ['country' => 'Cyprus', 'code' => 'CY', 'country_code' => '+357'],
				'358' => ['country' => 'Finland', 'code' => 'FI', 'country_code' => '+358'],
				'359' => ['country' => 'Bulgaria', 'code' => 'BG', 'country_code' => '+359'],
				'370' => ['country' => 'Lithuania', 'code' => 'LT', 'country_code' => '+370'],
				'371' => ['country' => 'Latvia', 'code' => 'LV', 'country_code' => '+371'],
				'372' => ['country' => 'Estonia', 'code' => 'EE', 'country_code' => '+372'],
				'373' => ['country' => 'Moldova', 'code' => 'MD', 'country_code' => '+373'],
				'374' => ['country' => 'Armenia', 'code' => 'AM', 'country_code' => '+374'],
				'375' => ['country' => 'Belarus', 'code' => 'BY', 'country_code' => '+375'],
				'376' => ['country' => 'Andorra', 'code' => 'AD', 'country_code' => '+376'],
				'377' => ['country' => 'Monaco', 'code' => 'MC', 'country_code' => '+377'],
				'378' => ['country' => 'San Marino', 'code' => 'SM', 'country_code' => '+378'],
				'380' => ['country' => 'Ukraine', 'code' => 'UA', 'country_code' => '+380'],
				'381' => ['country' => 'Serbia', 'code' => 'RS', 'country_code' => '+381'],
				'382' => ['country' => 'Montenegro', 'code' => 'ME', 'country_code' => '+382'],
				'383' => ['country' => 'Kosovo', 'code' => 'XK', 'country_code' => '+383'],
				'385' => ['country' => 'Croatia', 'code' => 'HR', 'country_code' => '+385'],
				'386' => ['country' => 'Slovenia', 'code' => 'SI', 'country_code' => '+386'],
				'387' => ['country' => 'Bosnia and Herzegovina', 'code' => 'BA', 'country_code' => '+387'],
				'389' => ['country' => 'North Macedonia', 'code' => 'MK', 'country_code' => '+389'],
				'420' => ['country' => 'Czech Republic', 'code' => 'CZ', 'country_code' => '+420'],
				'421' => ['country' => 'Slovakia', 'code' => 'SK', 'country_code' => '+421'],
				'423' => ['country' => 'Liechtenstein', 'code' => 'LI', 'country_code' => '+423'],
				'500' => ['country' => 'Falkland Islands', 'code' => 'FK', 'country_code' => '+500'],
				'501' => ['country' => 'Belize', 'code' => 'BZ', 'country_code' => '+501'],
				'502' => ['country' => 'Guatemala', 'code' => 'GT', 'country_code' => '+502'],
				'503' => ['country' => 'El Salvador', 'code' => 'SV', 'country_code' => '+503'],
				'504' => ['country' => 'Honduras', 'code' => 'HN', 'country_code' => '+504'],
				'505' => ['country' => 'Nicaragua', 'code' => 'NI', 'country_code' => '+505'],
				'506' => ['country' => 'Costa Rica', 'code' => 'CR', 'country_code' => '+506'],
				'507' => ['country' => 'Panama', 'code' => 'PA', 'country_code' => '+507'],
				'508' => ['country' => 'Saint Pierre and Miquelon', 'code' => 'PM', 'country_code' => '+508'],
				'509' => ['country' => 'Haiti', 'code' => 'HT', 'country_code' => '+509'],
				'590' => ['country' => 'Guadeloupe', 'code' => 'GP', 'country_code' => '+590'],
				'591' => ['country' => 'Bolivia', 'code' => 'BO', 'country_code' => '+591'],
				'592' => ['country' => 'Guyana', 'code' => 'GY', 'country_code' => '+592'],
				'593' => ['country' => 'Ecuador', 'code' => 'EC', 'country_code' => '+593'],
				'594' => ['country' => 'French Guiana', 'code' => 'GF', 'country_code' => '+594'],
				'595' => ['country' => 'Paraguay', 'code' => 'PY', 'country_code' => '+595'],
				'596' => ['country' => 'Martinique', 'code' => 'MQ', 'country_code' => '+596'],
				'597' => ['country' => 'Suriname', 'code' => 'SR', 'country_code' => '+597'],
				'598' => ['country' => 'Uruguay', 'code' => 'UY', 'country_code' => '+598'],
				'599' => ['country' => 'Netherlands Antilles', 'code' => 'AN', 'country_code' => '+599'],
				'670' => ['country' => 'East Timor', 'code' => 'TL', 'country_code' => '+670'],
				'672' => ['country' => 'Antarctica', 'code' => 'AQ', 'country_code' => '+672'],
				'673' => ['country' => 'Brunei', 'code' => 'BN', 'country_code' => '+673'],
				'674' => ['country' => 'Nauru', 'code' => 'NR', 'country_code' => '+674'],
				'675' => ['country' => 'Papua New Guinea', 'code' => 'PG', 'country_code' => '+675'],
				'676' => ['country' => 'Tonga', 'code' => 'TO', 'country_code' => '+676'],
				'677' => ['country' => 'Solomon Islands', 'code' => 'SB', 'country_code' => '+677'],
				'678' => ['country' => 'Vanuatu', 'code' => 'VU', 'country_code' => '+678'],
				'679' => ['country' => 'Fiji', 'code' => 'FJ', 'country_code' => '+679'],
				'680' => ['country' => 'Palau', 'code' => 'PW', 'country_code' => '+680'],
				'681' => ['country' => 'Wallis and Futuna', 'code' => 'WF', 'country_code' => '+681'],
				'682' => ['country' => 'Cook Islands', 'code' => 'CK', 'country_code' => '+682'],
				'683' => ['country' => 'Niue', 'code' => 'NU', 'country_code' => '+683'],
				'685' => ['country' => 'Samoa', 'code' => 'WS', 'country_code' => '+685'],
				'686' => ['country' => 'Kiribati', 'code' => 'KI', 'country_code' => '+686'],
				'687' => ['country' => 'New Caledonia', 'code' => 'NC', 'country_code' => '+687'],
				'688' => ['country' => 'Tuvalu', 'code' => 'TV', 'country_code' => '+688'],
				'689' => ['country' => 'French Polynesia', 'code' => 'PF', 'country_code' => '+689'],
				'690' => ['country' => 'Tokelau', 'code' => 'TK', 'country_code' => '+690'],
				'691' => ['country' => 'Micronesia', 'code' => 'FM', 'country_code' => '+691'],
				'692' => ['country' => 'Marshall Islands', 'code' => 'MH', 'country_code' => '+692'],
				'850' => ['country' => 'North Korea', 'code' => 'KP', 'country_code' => '+850'],
				'852' => ['country' => 'Hong Kong', 'code' => 'HK', 'country_code' => '+852'],
				'853' => ['country' => 'Macau', 'code' => 'MO', 'country_code' => '+853'],
				'855' => ['country' => 'Cambodia', 'code' => 'KH', 'country_code' => '+855'],
				'856' => ['country' => 'Laos', 'code' => 'LA', 'country_code' => '+856'],
				'880' => ['country' => 'Bangladesh', 'code' => 'BD', 'country_code' => '+880'],
				'886' => ['country' => 'Taiwan', 'code' => 'TW', 'country_code' => '+886'],
				'960' => ['country' => 'Maldives', 'code' => 'MV', 'country_code' => '+960'],
				'961' => ['country' => 'Lebanon', 'code' => 'LB', 'country_code' => '+961'],
				'962' => ['country' => 'Jordan', 'code' => 'JO', 'country_code' => '+962'],
				'963' => ['country' => 'Syria', 'code' => 'SY', 'country_code' => '+963'],
				'964' => ['country' => 'Iraq', 'code' => 'IQ', 'country_code' => '+964'],
				'965' => ['country' => 'Kuwait', 'code' => 'KW', 'country_code' => '+965'],
				'966' => ['country' => 'Saudi Arabia', 'code' => 'SA', 'country_code' => '+966'],
				'967' => ['country' => 'Yemen', 'code' => 'YE', 'country_code' => '+967'],
				'968' => ['country' => 'Oman', 'code' => 'OM', 'country_code' => '+968'],
				'970' => ['country' => 'Palestine', 'code' => 'PS', 'country_code' => '+970'],
				'971' => ['country' => 'United Arab Emirates', 'code' => 'AE', 'country_code' => '+971'],
				'972' => ['country' => 'Israel', 'code' => 'IL', 'country_code' => '+972'],
				'973' => ['country' => 'Bahrain', 'code' => 'BH', 'country_code' => '+973'],
				'974' => ['country' => 'Qatar', 'code' => 'QA', 'country_code' => '+974'],
				'975' => ['country' => 'Bhutan', 'code' => 'BT', 'country_code' => '+975'],
				'976' => ['country' => 'Mongolia', 'code' => 'MN', 'country_code' => '+976'],
				'977' => ['country' => 'Nepal', 'code' => 'NP', 'country_code' => '+977'],
				'992' => ['country' => 'Tajikistan', 'code' => 'TJ', 'country_code' => '+992'],
				'993' => ['country' => 'Turkmenistan', 'code' => 'TM', 'country_code' => '+993'],
				'994' => ['country' => 'Azerbaijan', 'code' => 'AZ', 'country_code' => '+994'],
				'995' => ['country' => 'Georgia', 'code' => 'GE', 'country_code' => '+995'],
				'996' => ['country' => 'Kyrgyzstan', 'code' => 'KG', 'country_code' => '+996'],
				'998' => ['country' => 'Uzbekistan', 'code' => 'UZ', 'country_code' => '+998'],
			];

			// Remove leading + if present
			$phone = ltrim($phone, '+');
			
			// Try to match country codes (try longest first - 3 digits, then 2, then 1)
			for ($length = 3; $length >= 1; $length--) {
				$code = substr($phone, 0, $length);
				if (isset($countryCodes[$code])) {
					return $countryCodes[$code];
				}
			}

			return ['country' => 'Unknown', 'code' => '', 'country_code' => ''];
		}
	}

	if(! function_exists('get_business_country_from_contactinfo')){
		function get_business_country_from_contactinfo($contactInfo)
		{
			if (empty($contactInfo) || empty($contactInfo->content)) {
				return ['country' => 'Unknown', 'code' => '', 'country_code' => ''];
			}

			$content = json_decode($contactInfo->content, true);
			
			if (!is_array($content)) {
				return ['country' => 'Unknown', 'code' => '', 'country_code' => ''];
			}

			// Look for Phone field in the JSON content
			$phone = null;
			foreach ($content as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $key1 => $val1) {
						if ($key1 == 'Phone' || $key1 == 'Telephone') {
							$phone = $val1;
							break 2;
						}
					}
				}
			}

			if (empty($phone)) {
				return ['country' => 'Unknown', 'code' => '', 'country_code' => ''];
			}

			return get_country_from_phone($phone);
		}
	}

?>
