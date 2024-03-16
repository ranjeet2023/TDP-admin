<?php

namespace App\Helpers;
use DB;
use Illuminate\Support\Facades\Auth;

use App\Models\UserHasPermission;
use App\Models\Notification;

class AppHelper
{
    public static function ChangeExcelTitleNatural($data)
	{
		$sheet_data = [];
		foreach($data as $key => $SheetTitle)
		{
			$SheetTitleElement = trim(str_replace(array(' ', '.','_'), '',strtolower($SheetTitle)));
			switch($SheetTitleElement)
			{
				case 'avail':
				case 'available':
				case 'status':
                case 'stockstatus':
					$sheet_data[$key] = 'availability';
					break;
                case 'packetno':
                case 'stoneid':
                case 'stockno':
                case 'refno':
                case 'supplierstockref':
				case 'stock':
				case 'stock#':
				case 'stock #':
				case 'vendorstocknumber':
				case 'stocknumber':
                case 'stocknum':
				case 'stockid':
                case 'lot#':
				case 'lotno':
				case 'stoneno':
				case 'barcode':
                case 'stoneproduct':
					$sheet_data[$key] = 'stock #';
					break;
				case 'carat':
				case 'size':
				case 'cts':
                case 'ct':
				case 'carats':
					$sheet_data[$key] = 'weight';
					break;
                case 'shp':
                case 'cut(shape)':
                    $sheet_data[$key] = 'shape';
                    break;
                case 'cla':
                case 'purity':
                    $sheet_data[$key] = 'clarity';
                    break;
				case 'col':
                case 'colour':
					$sheet_data[$key] = 'color';
					break;
				case 'cut':
				case 'cutgrade':
					$sheet_data[$key] = 'cut grade';
					break;
				case 'pol':
					$sheet_data[$key] = 'polish';
					break;
				case 'sym':
				case 'symm':
					$sheet_data[$key] = 'symmetry';
					break;
				case 'fluorescencecolor':
				case 'flour':
					$sheet_data[$key] = 'fluorescence color';
					break;
                case 'fls':
                case 'fluorescence':
                case 'fluorescenceintensity':
                case 'fl':
                case 'fluoint':
                    $sheet_data[$key] = 'fluorescence intensity';
                    break;
                case 'cert':
                case 'gradinglab':
                    $sheet_data[$key] = 'lab';
                    break;
				case 'report':
				case 'report#':
				case 'reportno':
				case 'certid':
				case 'certificateid':
				case 'certificatenum':
				case 'certificatenumber':
				case 'certno':
				case 'certnum':
				case 'cmpub':
                case 'repno':
				case 'certificate#':
				case 'certificate':
				case 'certnumber':
				case 'reportnumber':
				case 'certificateno':
					$sheet_data[$key] = 'certificate #';
					break;
				case 'dollarpercarat':
				case 'caratprice':
				case 'askingpricepercarat':
				case 'pricepercarat':
                case 'pricepercts':
				case 'per/cts($)':
				case 'rate':
				case '$percarat':
				case 'cashprice':
				case 'ppc':
                case 'us$/ct':
				case 'pr/ct':
				case 'grate':
				case 'price$/cts':
                case 'pricect':
		        case 'askingpricepercarat':
					$sheet_data[$key] = '$/ct';
					break;
				case 'dimensions':
				case 'meas':
				case 'measurement':
					$sheet_data[$key] = 'measurements';
					break;
				case 'tablepercent':
				case 'tablepct':
				case 'table':
				case 'tbl':
				case 'table%':
				case 'tab%':
					$sheet_data[$key] = 'table percent';
					break;
				case 'depthpercent':
				case 'depthpct':
				case 'dpth':
				case 'totaldepth':
				case 'depth%':
                case 'tdep':
				case 'td%':
				case 'depth':
					$sheet_data[$key] = 'depth percent';
					break;
				case 'ca':
				case 'crang':
				case 'crownangle':
					$sheet_data[$key] = 'crown angle';
					break;
				case 'ch':
                case 'crh':
				case 'crhgt':
                case 'crownheight':
					$sheet_data[$key] = 'crown height';
					break;
				case 'pa':
				case 'pvang':
				case 'pavlilionangle':
				case 'pavang':
				case 'pavilionangle':
					$sheet_data[$key] = 'pavilion angle';
					break;
				case 'ph':
				case 'pvhgt':
                case 'paviliondepth':
				case 'pavilionheight':
				case 'pavdpt':
                case 'pavh':
				case 'pavhgt':
					$sheet_data[$key] = 'pavilion depth';
					break;
				case 'location':
				case 'stonelocationcountry':
				case 'country':
				case 'branch':
                case 'loc':
					$sheet_data[$key] = 'country';
					break;
				case 'fancycolor':
					$sheet_data[$key] = 'fancy color';
					break;
				case 'fancycolorintensity':
					$sheet_data[$key] = 'fancy color intensity';
					break;
				case 'keytosymbols':
					$sheet_data[$key] = 'key to symbols';
					break;
				case 'culetsize':
				case 'culetgrade':
				case 'cullet':
				case 'culletgrade':
					$sheet_data[$key] = 'culet';
					break;
				case 'imageurl':
                case 'diamondimage':
                case 'imagelink':
                case 'diamondimageurl':
                case 'image':
                    $sheet_data[$key] = 'image link';
                    break;
                case 'link':
                case 'video':
                case 'diamondvideo':
                case 'videourl':
                case 'videolink':
                case 'diamondvideourl':
                case 'video360':
                    $sheet_data[$key] = 'video link';
                    break;
				case 'measwidth':
				case 'measurementswidth':
					$sheet_data[$key] = 'width';
					break;
				case 'measlength':
				case 'measurementslength':
					$sheet_data[$key] ='length';
					break;
				case 'measdepth':
				case 'measurementsdepth':
				case 'measheight':
					$sheet_data[$key] = 'height';
					break;
				case 'certcomment':
				case 'remarks':
				case 'labcomment':
				case 'certificatecomment':
				case 'laboratorycomment':
					$sheet_data[$key] = 'report comments';
					break;
				case 'membercomment':
				case 'vendorcomment':
				case 'suppliercomment':
				case 'sellercomment':
				case 'comments':
					$sheet_data[$key] = 'supplier comments';
					break;
				case 'tinge':
				case 'bgm':
				case 'colshade':
				case 'brown/green':
					$sheet_data[$key] = 'shade';
					break;
				case 'heartimage':
					$sheet_data[$key] = 'heart image';
					break;
				case 'arrowimage':
					$sheet_data[$key] = 'arrow image';
					break;
				case 'assetimage':
					$sheet_data[$key] = 'aset image';
					break;
				case 'girdleper':
					$sheet_data[$key] = 'girdle %';
					break;
				case 'milky/luster':
					$sheet_data[$key] = 'luster';
					break;
				case 'girdle':
					$sheet_data[$key] = 'girdle condition';
					break;
				default:
					$sheet_data[$key] = trim(strtolower($SheetTitleElement));
			}
		}
		return $sheet_data;
	}

	public static function ChangeExcelTitleLabGrown($data)
	{
        $sheet_data = [];
		foreach($data as $key => $SheetTitle)
		{
			$SheetTitleElement = trim(str_replace(array(' ', '.'.'_'), '',strtolower($SheetTitle)));
			switch($SheetTitleElement)
			{
				case 'avail':
				case 'available':
				case 'status':
                case 'stockstatus':
					$sheet_data[$key] = 'availability';
					break;
				case 'stock':
				case 'stock#':
				case 'vendorstocknumber':
				case 'stocknumber':
				case 'stockid':
				case 'packetno':
				case 'lotno':
				case 'stoneid':
                case 'stoneno':
				case 'stockno':
				case 'refno':
                case 'lot#':
                case 'barcode':
					$sheet_data[$key] = 'stock #';
					break;
				case 'weight':
				case 'carat':
				case 'size':
				case 'cts':
                case 'ct':
				case 'carats':
					$sheet_data[$key] = 'weight';
					break;
				case 'cut':
				case 'cutgrade':
					$sheet_data[$key] = 'cut grade';
					break;
				case 'col':
				case 'colour':
					$sheet_data[$key] = 'color';
					break;
				case 'fluorescencecolor':
				case 'flour':
                case 'fluor':
					$sheet_data[$key] = 'fluorescence color';
					break;
				case 'report':
				case 'report#':
				case 'reportno':
				case 'certid':
				case 'certificateid':
				case 'certificatenum':
				case 'certnumber':
				case 'certificatenumber':
				case 'reportnumber':
				case 'certno':
				case 'certnum':
				case 'cmpub':
				case 'certificate#':
				case 'certificate':
                case 'repno':
                case 'certificateno':
					$sheet_data[$key] = 'certificate #';
					break;
				case 'dollarpercarat':
				case 'caratprice':
				case 'askingpricepercarat':
				case 'pricepercarat':
				case 'percarat':
				case 'per/cts($)':
				case 'rate($)':
				case 'rate':
				case 'ppc':
                case 'us$/ct':
				case 'pr/ct':
				case 'grate':
				case 'price$/cts':
				// case 'rapnetprice':
				case 'cashprice':
                case '$/ct':
                case 'pricect':
					$sheet_data[$key] = '$/ct';
					break;
				case 'dimensions':
				case 'meas':
				case 'measurement':
				case 'measurements':
                case 'mesurement':
					$sheet_data[$key] = 'measurements';
					break;
				case 'tablepercent':
				case 'tablepct':
				case 'table':
				case 'tbl':
				case 'table%':
				case 'tab%':
				case 'tablepercentage':
					$sheet_data[$key] = 'table percent';
					break;
				case 'depthpercent':
				case 'depthpct':
				case 'dpth':
				case 'totaldepth':
				case 'depth%':
				case 'td%':
                case 'tdep':
                case 'td':
				case 'depthpercentage':
				// case 'depth':
					$sheet_data[$key] = 'depth percent';
					break;
				case 'fls':
				case 'fluorescence':
                case 'fluorescenceintensity':
				case 'fl':
				case 'fluoint':
					$sheet_data[$key] = 'fluorescence intensity';
					break;
				case 'imageurl':
				case 'diamondimage':
				case 'imagelink':
				case 'diamondimageurl':
				case 'image':
					$sheet_data[$key] = 'image link';
					break;
				case 'link':
				case 'video':
				case 'diamondvideo':
				case 'videourl':
				case 'videolink':
                case 'video360':
				case 'diamondvideourl':
					$sheet_data[$key] = 'video link';
					break;
				case 'pol':
					$sheet_data[$key] = 'polish';
					break;
				case 'sym':
				case 'symm':
					$sheet_data[$key] = 'symmetry';
					break;
				case 'shp':
					$sheet_data[$key] = 'shape';
					break;
				case 'ca':
				case 'crang':
				case 'crownangle':
					$sheet_data[$key] = 'crown angle';
					break;
				case 'ch':
				case 'crhgt':
                case 'crownheight':
					$sheet_data[$key] = 'crown height';
					break;
				case 'pa':
				case 'pvang':
				case 'pavlilionangle':
                case 'pavilionangle':
				case 'pavang':
					$sheet_data[$key] = 'pavilion angle';
					break;
				case 'ph':
				case 'pvhgt':
				case 'pavilionheight':
                case 'paviliondepth':
				case 'pavdpt':
				case 'pavhgt':
					$sheet_data[$key] = 'pavilion depth';
					break;
				case 'location':
				case 'country':
                case 'loc':
                case 'branch':
				case 'stonelocationcountry':
					$sheet_data[$key] = 'country';
					break;
				case 'fancycolor':
					$sheet_data[$key] = 'fancy color';
					break;
				case 'fancycolorintensity':
					$sheet_data[$key] = 'fancy color intensity';
					break;
				case 'keytosymbols':
					$sheet_data[$key] = 'key to symbol';
					break;
				case 'culetsize':
				case 'culetsize':
				case 'culetgrade':
				case 'cullet':
				case 'culletgrade':
					$sheet_data[$key] = 'culet';
					break;
                case 'w':
				case 'measwidth':
				case 'measurementswidth':
				case 'measurementwidth':
					$sheet_data[$key] = 'width';
					break;
                case 'l':
				case 'measlength':
				case 'lenth':
				case 'len':
				case 'measurementslength':
                case 'measurentlegth':
					$sheet_data[$key] ='length';
					break;
                case 'h':
				case 'measdepth':
				case 'measurementsdepth':
                case 'measurementdepth':
				case 'measheight':
				case 'height':
				case 'depth':
					$sheet_data[$key] = 'height';
					break;
				case 'certcomment':
				case 'remarks':
				case 'labcomment':
				case 'certificatecomment':
				case 'laboratorycomment':
					$sheet_data[$key] = 'report comments';
					break;
				case 'membercomment':
				case 'vendorcomment':
				case 'suppliercomment ':
				case 'sellercomment':
				case 'comments':
					$sheet_data[$key] = 'supplier comments';
					break;
				case 'tinge':
				case 'bgm':
				case 'colshade':
				case 'brown/green':
					$sheet_data[$key] = 'shade';
					break;
				case 'cla':
				case 'purity':
					$sheet_data[$key] = 'clarity';
					break;
				case 'cert':
					$sheet_data[$key] = 'lab';
					break;
				case 'heartimage':
					$sheet_data[$key] = 'heart image';
					break;
				case 'arrowimage':
					$sheet_data[$key] = 'arrow image';
					break;
				case 'assetimage':
					$sheet_data[$key] = 'aset image';
					break;
				case 'girdleper':
					$sheet_data[$key] = 'girdle percent';
					break;
				case 'milky/luster':
					$sheet_data[$key] = 'luster';
					break;
				case 'girdle':
					$sheet_data[$key] = 'girdle condition';
					break;
                case 'type':
                case 'type2a':
                case 'growthtype':
				case 'technology':
                case 'labgrowntype':
                case 'compnaycomment':
				// case 'certcomment':
                    $sheet_data[$key] = 'treatment';
                    break;
				default:
					$sheet_data[$key] = trim(strtolower($SheetTitleElement));
			}
		}
		return $sheet_data;
	}

	public static function ChangeTitleNatural($data)
	{
		$data = [];
		foreach ($data as $key => $Title) {
			$TitleElement = trim(str_replace(" ", "", strtolower($Title)));
			switch ($TitleElement) {
				case 'avail':
				case 'available':
				case 'status':
                case 'stockstatus':
					$sheet_data[$key] = 'availability';
					break;
				case 'stock': //ratnakala //shivamjewels
				case 'stock#': //vaibhav_gems
				case 'packet_no': //bbipin //finestar
				case 'packetcode': //ankit
				case 'lot_no': //vrustar
				case 'stockno': //hare_krishna
				case 'refno': //sagar_enterprise //parishi_diamond
				case 'field_stock_refno': //angel_star
				case 'stone_no': //africanstar //excellent
				case 'stock_no': //belgiumny
				case 'stoneid': //pansuriya //shyam_co
				case 'pid': //snjdiam
				case 'stockid': //sunrise
				case 'stoneno': //hvk //ozonediam
				case 'packetid': //kumarjewels
				case 'stockno.': //excelsuccess
					$sheet_data[$key] = 'stock #';
					break;
				case 'carat': //vrustar //hare_krishna
				case 'cts': //bbipin //finestar
				case 'crtwt': //ankit
				case 'field_stock_weight': //angel_star
				case 'size': //shyam_co
				case 'carats': //ozonediam
					$sheet_data[$key] = 'weight';
					break;
				case 'cutgrade': //ratnakala //vaibhav_gems
				case 'field_stock_cut': //angel_star
				case 'cut_grade': //belgiumny
				case 'propcode': //sagar_enterprise //parishi_diamond
					$sheet_data[$key] = 'cut';
					break;
				case 'col': //ankit
				case 'colorcode': //sagar_enterprise //parishi_diamond
				case 'field_stock_color': //angel_star
				case 'colour': //excelsuccess
				case 'colorname': //vinaydiamonds
					$sheet_data[$key] = 'color';
					break;
				case 'lab.':
				case 'certi_name':
				case 'labname': //vinaydiamonds
					$sheet_data[$key] = 'lab';
					break;
				case 'clr': //ankit
				case 'clarityname': //sagar_enterprise //parishi_diamond
				case 'field_stock_clarity': //angel_star
				case 'purity': //bbipin //finestar
				case 'cal_name': //snjdiam
				case 'purityname': //vinaydiamonds
					$sheet_data[$key] = 'clarity';
					break;
				case 'certno': //sagar_enterprise //parishi_diamond
				case 'certificate#': //ratnakala
				case 'certificate': //belgiumny //shivamjewels
				case 'cert_no': //ankit //hare_krishna
				case 'certificate_no': //vrustar //excellent //excellent
				case 'field_stock_certno': //angel_star
				case 'report_no': //bbipin
				case 'lab_report_no': //africanstar
				case 'report_no': //finestar
				case 'reportno': //pansuriya
				case 'certino.': //sunrise
				case 'labreportno': //hvk
				case 'certificateno': //shyam_co
				case 'certificatenumber': //vaibhav_gems
				case 'certificate.': //excelsuccess
				case 'certino': //narolagems
					$sheet_data[$key] = 'certificate #';
					break;
				case 'rate': //sagar_enterprise //parishi_diamond //bbipin
				case 'cashprice': //ratnakala
				case 'rte': //ankit
				case 'price_per_cts': //vrustar
				case 'pr/ct': //hare_krishna
				case 'field_stock_pr_pc': //angel_star
				case 'salerate': //africanstar
				case 'buy_price': //belgiumny
				case 'net_rate': //bbipin //finestar
				case 'sell_rate':
				case 'percarat': //shivamjewels
				case 'pricepercarat': //pansuriya
				case 'c__cts_': //snjdiam
				case 'rapprice($)': //sunrise
				case 'websiterate': //hvk
				case 'price': //shyam_co
				case 'rapnetprice': //excelsuccess
				case 'raprate': //vinaydiamonds
				case 'netrate': //ozonediam
				case 'onlineprice': //kpsanghvi
				case 'askingpricepercarat': //hriddesh
					$sheet_data[$key] = '$/ct';
					break;
				case 'measurement': //hare_krishna //africanstar //finestar
				case 'diameter': //sagar_enterprise //parishi_diamond
				case 'field_stock_diameter': //angel_star
				case 'measurment': //vinaydiamonds
					$sheet_data[$key] = 'measurements';
					break;
				case 'table': //sagar_enterprise //parishi_diamond //shivamjewels
				case 'tbl': //ankit
				case 'table_per': //vrustar //bbipin //belgiumny //finestar
				case 'tab%': //hare_krishna
				case 'field_stock_table': //angel_star
				case 'table_diameter_per': //africanstar
				case 'tab': //snjdiam
				case 'tableper': //sunrise //hvk
				case 'tablepercent': //shyam_co
				case 'totaltable': //kpsanghvi
					$sheet_data[$key] = 'table %';
					break;
				case 'dpl': //ankit
				case 'depth_per': //vrustar //bbipin //belgiumny //finestar
				case 'td%': //hare_krishna
				case 'totdepth': //sagar_enterprise //parishi_diamond
				case 'field_stock_depth': //angel_star
				case 'total_depth_per': //africanstar
				case 'depth': //shivamjewels
				case 'totaldepth': //snjdiam
				case 'depthper': //sunrise
				case 'depthpercent': //shyam_co
				case 'td': //vinaydiamonds
					$sheet_data[$key] = 'depth %';
					break;
				case 'fls': //bbipin //finestar
				case 'flouresence': //ankit
				case 'fluro': //vrustar
				case 'fl': //hare_krishna
				case 'flname': //sagar_enterprise //parishi_diamond
				case 'field_stock_fluorescence': //angel_star
				case 'flrintens': //africanstar
				case 'fluorescence_intensity': //belgiumny
				case 'fluorescence': //shyam_co
				case 'flo': //snjdiam
				case 'flu': //hvk
				case 'flor': //kumarjewels
				case 'fluorescenceintensity': //excelsuccess
				case 'flour': //narolagems
				case 'fluoname': //vinaydiamonds
				case 'fluoresence': //kpsanghvi
					$sheet_data[$key] = 'fluorescence intensity';
					break;
				case 'videos': //ankit
				case 'field_stock_videopath': //angel_star
				case 'video_url': //africanstar
				case 'videolink': //hare_krishna //belgiumny
				case 'videopath': //parishi_diamond
				case 'b2b360video': //
				case 'movielink': //snjdiam
				case 'videourl': //kumarjewels
				case 'moviepath': //narolagems
				case 'v360link': //kpsanghvi
					$sheet_data[$key] = 'video';
					break;
				case 'diamondimg': //ankit
				case 'field_stock_diamond_image': //angel_star
				case 'stone_img_url': //africanstar
				case 'imagelink': //belgiumny
				case 'image': //vrustar
				case 'imagepath': //parishi_diamond
				case 'real_image': //finestar
				case 'b2bimage': //
				case 'imageurl': //kumarjewels
				case 'photopath': //narolagems
					$sheet_data[$key] = 'image link';
					break;
				case 'pol': //ankit //hare_krishna
				case 'polishname': //sagar_enterprise //parishi_diamond
				case 'field_stock_polish': //angel_star
					$sheet_data[$key] = 'polish';
					break;
				case 'sym': //ankit //hare_krishna
				case 'symm': //vrustar //bbipin //africanstar //finestar
				case 'symname': //sagar_enterprise //parishi_diamond
				case 'field_stock_symmetry': //angel_star
					$sheet_data[$key] = 'symmetry';
					break;
				case 'shp': //ratnakala
				case 'cutname': //sagar_enterprise //parishi_diamond
				case 'field_stock_shape': //angel_star
				case 'shp_name': //snjdiam
				case 'shapename': //vinaydiamonds
					$sheet_data[$key] = 'shape';
					break;
				case 'ca': //ankit //sagar_enterprise //parishi_diamond
				case 'crang': //hare_krishna
				case 'field_stock_ca': //angel_star
				case 'crown_angle': //bbipin //belgiumny //finestar
				case 'crownangle': //vrustar //africanstar //shivamjewels //snjdiam //shyam_co
				case 'crang': //sunrise
				case 'crownang': //hvk
				case 'cangle': //narolagems
					$sheet_data[$key] = 'crown angle';
					break;
				case 'ch': //ankit //sagar_enterprise //parishi_diamond
				case 'crhgt': //hare_krishna
				case 'field_stock_ch': //angel_star
				case 'crown_height': //bbipin //belgiumny //finestar
				case 'crownheight': //vrustar //africanstar //shivamjewels //snjdiam //hvk //shyam_co
				case 'crht': //sunrise
				case 'cheight': //narolagems
					$sheet_data[$key] = 'crown height';
					break;
				case 'pa': //ankit //sagar_enterprise //parishi_diamond
				case 'pvang': //hare_krishna
				case 'field_stock_pa': //angel_star
				case 'pavillion_angle': //bbipin
				case 'pavillionangle': //vrustar //africanstar
				case 'pavilion_angle': //belgiumny
				case 'pav_angle': //finestar
				case 'pavilionangle': //shivamjewels //shyam_co
				case 'pavangle'://snjdiam
				case 'pavang': //sunrise //hvk
				case 'pangle': //narolagems
				case 'pavalianangle':
				case 'pavlilionangle': //Rose360
					$sheet_data[$key] = 'pavilion angle';
					break;
				case 'pavilionper': //sagar_enterprise //parishi_diamond
				case 'pd': //ankit
				case 'pvhgt': //hare_krishna
				case 'field_stock_ph': //angel_star
				case 'pavillion_height': //bbipin
				case 'pavillionheight': //vrustar //africanstar
				case 'pavilion_depth': //belgiumny
				case 'pav_height': //finestar
				case 'paviliondepth': //shivamjewels //shyam_co
				case 'pavheight'://snjdiam
				case 'pavht': //sunrise
				case 'pavdepth': //hvk
				case 'pavhgt': //kumarjewels
				case 'pheight': //narolagems
				case 'pavalianheight': //vinaydiamonds
				case 'pavilionheight': //kpsanghvi
				case 'pavilliondepth': //Rose360
					$sheet_data[$key] = 'pavilion depth';
					break;
				case 'location': //vrustar //hare_krishna  //sagar_enterprise //parishi_diamond //bbipin //africanstar //finestar
				case 'loc': //ankit
				case 'field_stock_location': //angel_star
				case 'country': //shyam_co
					$sheet_data[$key] = 'country';
					break;
				case 'girdle': //ankit //vrustar //hare_krishna //finestar
				case 'field_stock_girdlecondition': //angel_star
				case 'girdle_cond': //bbipin
				case 'girdlecon': //africanstar
				case 'girdle_max': //belgiumny
				case 'girdledesc': //snjdiam //narolagems
				case 'girdcon.': //sunrise
				case 'girdlename': //vinaydiamonds
				case 'girdlecondition': //excelsuccess
					$sheet_data[$key] = 'girdle condition';
					break;
				case 'fancycolor': //vaibhav_gems //sagar_enterprise //parishi_diamond
					$sheet_data[$key] = 'fancy color';
					break;
				case 'fancycolorintensity': //vaibhav_gems //sagar_enterprise //parishi_diamond
					$sheet_data[$key] = 'fancy color intensity';
					break;
				case 'ktsview': //ankit
				case 'keytosymbol': //hare_krishna
				case 'keytosymbols': //sagar_enterprise //parishi_diamond //africanstar //hvk
				case 'field_stock_keytosymbols': //angel_star //bbipin
				case 'key_to_symbols': //belgiumny //finestar
				case 'kts': //vrustar
					$sheet_data[$key] = 'key to symbols';
					break;
				case 'assetimage': //vaibhav_gems
				case 'asset_path': //ankit
				case 'asset_image': //vrustar
					$sheet_data[$key] = 'aset image';
					break;
				case 'girdle_per': //africanstar //belgiumny
				case 'grirdle_': //snjdiam
				case 'girdlepercent': // shyam_co
				case 'girdleheight': //vinaydiamonds
				case 'girdleperc': //kpsanghvi
				case 'girdleper': //excelsuccess
				case 'girdle%':
					$sheet_data[$key] = 'girdle %';
					break;
				case 'field_stock_culet': //angel_star
				case 'culet_size': //belgiumny
				case 'culetsize': // shyam_co
				case 'culet': //kumarjewels //narolagems
				case 'culet.': //excelsuccess
				case 'culetname': //vinaydiamonds
					$sheet_data[$key] = 'culet';
					break;
				case 'culetcon': //africanstar
					$sheet_data[$key] = 'culet condition';
					break;
				case 'certname': //sagar_enterprise //parishi_diamond
				case 'field_stock_certificate': //angel_star
				case 'cr_name': //snjdiam
				case 'certtype': //kpsanghvi
					$sheet_data[$key] = 'lab';
					break;
				case 'field_stock_eyeclean': //angel_star
				case 'eye_clean': //ankit //vrustar //bbipin //belgiumny //finestar
				case 'eyeclean': //africanstar
				case 'ec':
					$sheet_data[$key] = 'eye clean';
					break;
				case 'length_1': //bbipin
				case 'dia_mn':
					$sheet_data[$key] = 'length';
					break;
				case 'width': //bbipin
				case 'dia_mx':
					$sheet_data[$key] = 'width';
					break;
				case 'ht':
				case 'height': //hvk
				case 'depthmm': //excelsuccess
				case 'measurementsdepth':
				case 'measurementdepth':
				case 'measheight':
					$sheet_data[$key] = 'height';
					break;
				case 'heart_image': //finestar
					$sheet_data[$key] = 'heart image';
					break;
				case 'arrow_image': //finestar
					$sheet_data[$key] = 'arrow image';
					break;
				case 'membercomment':
				case 'vendorcomment':
				case 'suppliercomment ':
				case 'sellercomment':
					$sheet_data[$key] = 'supplier comments';
					break;
				case 'tinge':
				case 'bgm':
				case 'colshade':
				case 'colorshade':
					$sheet_data[$key] = 'shade';
					break;
				case 'girdlethin':
					$sheet_data[$key] = 'girdle thin';
					break;
				case 'girdlethick':
					$sheet_data[$key] = 'girdle thick';
					break;
				default:
					$sheet_data[$key] = trim(strtolower($TitleElement));
			}
		}
		return $sheet_data;
	}

	public static function ChangeTitleLabGrown($data)
	{
		$sheet_data = [];
		foreach($data as $key => $Title)
		{
			$TitleElement = trim(str_replace(" ","",strtolower($Title)));
			switch($TitleElement)
			{
				case 'avail':
				case 'available':
				case 'status':
                case 'stockstatus':
					$sheet_data[$key] = 'availability';
					break;
				case 'stock#':  //brahmadiamond
				case 'packetid': //blumoon
				case 'stone_x0020_id': //paralleldiamonds
					$sheet_data[$key] = 'stock #';
					break;
				case 'cutgrade': //brahmadiamond,
				case 'cut': //blumoon //paralleldiamonds
					$sheet_data[$key] = 'cut grade';
					break;
				case 'col':
					$sheet_data[$key] = 'color';
					break;
				case 'measurement': //paralleldiamonds
					$sheet_data[$key] = 'measurements';
					break;
				case 'caretweight': //blumoon
				case 'carat': //paralleldiamonds
					$sheet_data[$key] = 'weight';
					break;
				case 'certificate#': //brahmadiamond
				case 'certificateno': //blumoon
				case 'certificate_x0020_no': //paralleldiamonds
					$sheet_data[$key] = 'certificate #';
					break;
				case 'rapnetprice': //brahmadiamond,
				case 'price': //surajmal
				case 'pricpercaret': //blumoon
				case 'price_x002f_ct': //paralleldiamonds
					$sheet_data[$key] = '$/ct';
					break;
				case 'table%': //brahmadiamond,
				case 'tbl': //blumoon
				case 'tab_x0025_': //paralleldiamonds
					$sheet_data[$key] = 'table percent';
					break;
				case 'depth%': //brahmadiamond,
				case 'dp': //blumoon
				case 'totdepth_x0020__x0025_': //paralleldiamonds
					$sheet_data[$key] = 'depth percent';
					break;
				case 'diamondimage': //brahmadiamond
				case 'imagelink': //blumoon
				case 'image': //paralleldiamonds
					$sheet_data[$key] = 'image link';
					break;
				case 'diamond video': //brahmadiamond
				case 'diamondvideo': //brahmadiamond
				case 'videolink': //blumoon
				case 'video': //paralleldiamonds
					$sheet_data[$key] = 'video link';
					break;
				case 'fancycolor': //brahmadiamond,
					$sheet_data[$key] = 'fancy color';
					break;
				case 'fancycolorintensity': //brahmadiamond,
					$sheet_data[$key] = 'fancy color intensity';
					break;
				case 'keytosymbols': //brahmadiamond
				case 'key_x0020_to_x0020_sym': //paralleldiamonds
					$sheet_data[$key] = 'key to symbol';
					break;
				case 'culetsize': //surajmal
					$sheet_data[$key] = 'culet';
					break;
				case 'flourence': //blumoon
					$sheet_data[$key] = 'fluorescence intensity';
					break;
				case 'certificatelab': //blumoon
					$sheet_data[$key] = 'lab';
					break;
				case 'symentry': //blumoon
					$sheet_data[$key] = 'symmetry';
					break;
				case 'girdle': //blumoon
					$sheet_data[$key] = 'girdle condition';
					break;
				case 'cr_x0020_hgt': //paralleldiamonds
					$sheet_data[$key] = 'crown height';
					break;
				case 'cr_x0020_ang': //paralleldiamonds
					$sheet_data[$key] = 'crown angle';
					break;
				case 'pv_x0020_ang': //paralleldiamonds
					$sheet_data[$key] = 'pavilion angle';
					break;
				case 'pv_x0020_hgt': //paralleldiamonds
					$sheet_data[$key] = 'pavilion depth';
					break;
				case 'location': //paralleldiamonds
					$sheet_data[$key] = 'country';
					break;
				case 'eye_x0020_clean': //paralleldiamonds
					$sheet_data[$key] = 'eye clean';
					break;
				case 'measurementsdepth':
				case 'measurementdepth':
				case 'measheight':
					$sheet_data[$key] = 'height';
					break;
				case 'membercomment':
				case 'vendorcomment':
				case 'suppliercomment ':
				case 'sellercomment':
					$sheet_data[$key] = 'supplier comments';
					break;
				case 'tinge':
				case 'bgm':
				case 'colshade':
					$sheet_data[$key] = 'shade';
					break;
                case 'type':
                case 'type2a':
                case 'growthtype':
                case 'technology':
                case 'labgrowntype':
                case 'compnaycomment':
                // case 'certcomment':
                    $sheet_data[$key] = 'treatment';
                    break;
				default:
					$sheet_data[$key] = trim(strtolower($TitleElement));
			}
		}
		return $sheet_data;
	}

	public static function NaturalCondition($data, $shape)
	{
		$status = array();
		$status['success'] = true;
		$reason = '';

		if ($shape == "") {
			$reason .= "SHAPE, ";
			$status['success'] = false;
		}

		// if (!in_array(strtoupper(trim($data['availability'])), array('','G', 'AVAILABLE','GUARANTEED AVAILABLE', 'A', 'AV', 'GA', 'B', 'M', 'H', 'NA', 'STOCK','MEMO', 'BUSY', 'HOLD'))) {
		// 	$reason .= "Availability, ";
		// 	$status['success'] = false;
		// }

		$data['weight'] = trim(@$data['weight']);
		if ((@$data['weight'] < "0.07" && $data > "999") || !is_numeric(@$data['weight'])) {
			$reason .= "WEIGHT, ";
			$status['success'] = false;
		} else if (preg_match('/\.\d{4,}/', @$data['weight']))
		{
			$reason .= "CARAT UP TO 3 DECIMALS, ";
			$status['success'] = false;
		}

		if (strtoupper(trim(@$data['color'])) == "FANCY" || @$data['color'] == "*" || @$data['color'] == "") {

			if (empty(@$data['fancy color'])) {
				$reason .= "FANCY COLOR, ";
				$status['success'] = false;
			} else {
				$folor = strtoupper(trim(@$data['fancy color']));
				$folor = str_replace("-", " ", $folor);
				$fc = explode(" ", $folor);
				$haystack = array('BK', 'BLACK', 'B', 'BLUE', 'BN', 'BROWN', 'CH', 'CHAMELEON', 'CG', 'COGNAC', 'GY', 'GRAY', 'GREY', 'KG', 'GREEN', 'O', 'ORANGE', 'P', 'PINK', 'PL', 'PURPLE', 'R', 'RED', 'V', 'VIOLET', 'Y', 'YELLOW', 'W', 'WHITE', 'OT', 'X', 'OTHER');
				if (count(array_intersect($haystack, $fc)) != count($fc)) {
					$reason .= "FANCY COLOR, ";
					$status['success'] = false;
				}
			}

			if (empty(@trim(@$data['fancy color intensity'])) || !in_array(strtoupper(@$data['fancy color intensity']), array('F', 'FAINT', 'VERY LIGHT', 'VL', 'LIGHT', 'L', 'FANCY LIGHT', 'FL', 'FCL', 'FANCY DARK', 'DARK', 'FCD', 'FANCY', 'FC', 'FANCY INTENSE', 'I', 'FI', 'INTENSE', 'FANCY VIVID', 'VIVID', 'V', 'FV', 'D', 'FD', 'DEEP', 'FANCY DEEP'))) {
				$reason .= "FANCY COLOR INTENSITY, ";
				$status['success'] = false;
			}

			$fovertone = strtoupper(trim(@$data['fancy color overtone']));
			$fovertone = str_replace("-", " ", $fovertone);
			$fover = explode(" ", $fovertone);
			$haystack = array('', 'NONE', 'YELLOW', 'YELLOWISH', 'PINK', 'PINKISH', 'BLUE', 'BLUISH', 'RED', 'REDDISH', 'GREEN', 'GREENISH', 'PURPLE', 'PURPLISH', 'ORANGE', 'ORANGEY', 'VIOLET', 'GRAY', 'GRAYISH', 'BLACK', 'BROWN', 'BROWNISH', 'CHAMPANGE', 'COGNAC', 'CHAMELEON', 'VIOLETISH', 'WHITE', 'OTHER');
			if (!empty(@$data['fancy color overtone']) && count(array_intersect($haystack, $fover)) != count($fover)) {
				$reason .= "FANCY COLOR OVERTONE, ";
				$status['success'] = false;
			}
		} else {
			if (!in_array(str_replace(array('-', '+',' '), "",strtoupper(trim(@$data['color']))), array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'OP', 'QR', 'ST', 'UV', 'WX', 'YZ', 'O-P', 'Q-R', 'S-T', 'U-V', 'W-X', 'Y-Z'))) {
				$reason .= "COLOR, ";
				$status['success'] = false;
			}
			// if (!empty(trim(@$data['color'])) && (!empty(@trim(@$data['fancy color'])) || !empty(@trim(@$data['fancy color intensity'])) || !empty(@trim(@$data['fancy color overtone'])))) {
			// 	$reason .= "COLOR, ";
			// 	$status['success'] = false;
			// }
		}

		if (!in_array(strtoupper(trim(str_replace(array('-', '+',' '), "", @$data['clarity']))), array('FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1', 'I2', 'I3'))) {
			$reason .= "CLARITY, ";
			$status['success'] = false;
		}

		$cutarraywithblank = array(
			'','e','ex','exc','excellent','i',
			'id','ideal','f','fr','fair',
			'g','gd','good','v','vg',
			'verygood','p','pr','poor','**',
			'na','3ex','exideal'
		);
		if ($shape == "ROUND") {
			if (strtoupper(trim(@$data['color'])) != "FANCY" || trim(@$data['color']) != "*" || trim(@$data['color']) != "") {
				if (!in_array(trim(str_replace(array(' ','-','.','&',',','+'),'',strtolower(@$data['cut']))),$cutarraywithblank)) {
					$reason .= "Cut, ";
					$status['success'] = false;
				}
			} else {
				$cutarraywithoutblank = array(
					'e','ex','exc','excellent','i',
					'id','ideal','f','fr','fair',
					'g','gd','good','v','vg',
					'verygood','p','pr','poor','**',
					'na','3ex','exideal'
				);
				if (!in_array(trim(str_replace(array(' ','-','.','&',',','+'),'',strtolower(@$data['cut']))),$cutarraywithoutblank)) {
					$reason .= "Cut, ";
					$status['success'] = false;
				}
			}

		} else {
			if (!in_array(trim(str_replace(array('n/a','na','none',' ','-','.','&',',','+'),'',strtolower(@$data['cut']))),$cutarraywithblank)) {
				$reason .= "CUT, ";
				$status['success'] = false;
			}
		}

		if (!in_array(strtoupper(trim(@$data['polish'])), array('I', 'ID', 'IDEAL', 'EX', 'EXC', 'EXCELLENT', 'VG', 'VERY GOOD', 'G', 'GD', 'GOOD', 'F', 'FR', 'FAIR', 'P', 'PR', 'POOR'))) {
			$reason .= "POLISH, ";
			$status['success'] = false;
		}
		if (!in_array(strtoupper(trim(@$data['symmetry'])), array('I', 'ID', 'IDEAL', 'EX', 'EXC', 'EXCELLENT', 'VG', 'VERY GOOD', 'G', 'GD', 'GOOD', 'F', 'FR', 'FAIR', 'P', 'PR', 'POOR'))) {
			$reason .= "SYMMETRY, ";
			$status['success'] = false;
		}

		$fluorescenceintensityarray  = array(
			'','n','none','non','no','nil','fl0','nn',
			'f','faint','fnt','negligible','fa','fayl',
			'fl','fl1','m','me','medium','med','fl2','md',
			'mdbl','mdyl','mb','mediumblue','s','strong','strongblue',
			'stg','st','stbl','styl','fl3','sb','vs','verystrong','vst',
			'vstb','fl4','vsl','vstg','vstrong','vstrgblue','vsl','vslg',
			'vslt','vsli','veryslight','vslight','sl','sli','slt','slg','slight',
			'str','vstr'
		);

		if (!in_array(trim(str_replace(array(' ','-','.','&',','),'',strtolower(@$data['fluorescence intensity']))),$fluorescenceintensityarray)) {
			$reason .= 'FLUORESCENCE, ';
			$status['success'] = false;
		}

		$mesurment = str_replace(array(" ",' '), "", @$data['measurements']);
		$mesurment = str_replace(array('*', '-', 'x', "mm"), "x", strtolower($mesurment));
		$main = explode("x", $mesurment);
		$C_Length = (!empty($main[0])) ? trim($main[0]) : trim(@$data['length']);
		$C_Width = (!empty($main[1])) ? trim($main[1]) : trim(@$data['width']);
		$C_Depth = (!empty($main[2])) ? trim($main[2]) : trim(@$data['height']);
		if (empty($C_Length) || !is_numeric($C_Length) || $C_Length < 1 || $C_Length > 100) {
			$reason .= "MEASUREMENTS LENGTH, ";
			$status['success'] = false;
		}
		if (empty($C_Width) || !is_numeric($C_Width) || $C_Width < 1 || $C_Width > 100) {
			$reason .= "MEASUREMENTS WIDTH, ";
			$status['success'] = false;
		}
		if (empty($C_Depth) || !is_numeric($C_Depth) || $C_Depth < 1 || $C_Depth > 100) {
			$reason .= "MEASUREMENTS DEPTH, ";
			$status['success'] = false;
		}

		if (!in_array(strtoupper(trim(@$data['lab'])), array('G', 'GIA', 'H', 'HRD', 'I', 'IGI', 'A', 'AGS'))) {
			$reason .= "LAB, ";
			$status['success'] = false;
		}
		if (empty(trim(@$data['certificate #'])) || !is_numeric(str_replace(array("S4A", "S3G", "S3H", "LGM3C","LGM3E", "LG"), "", @$data['certificate #']))) { //|| floor(@$data['N']) != @$data['N']
			$reason .= "CERTIFICATE, ";
			$status['success'] = false;
		}

		if(array_key_exists ('$/ct' , $data))
		{
			$doller_per = str_replace(array(",", "$"), "", trim(@$data['$/ct']));
		}
		else if(array_key_exists ('price$' , $data))
		{
			$doller_per = str_replace(array(",", "$"), "", trim(@$data['price$']));
		}

		if (empty($doller_per) || $doller_per < 0 || !is_numeric($doller_per)) {
			$reason .= "DOLLER PER CARAT, ";
			$status['success'] = false;
		}

		$depth = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['depth percent']));
		if (empty($depth) || !is_numeric($depth) || $depth < 0.01 || $depth > 100) {
			$reason .= "Depth, ";
			$status['success'] = false;
		}

		$table = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['table percent']));
		if (empty($table) || !is_numeric($table) || $table < 0.01 || $depth > 100) {
			$reason .= "Table, ";
			$status['success'] = false;
		}

		if ($shape == 'ROUND') {
			$ch_ht = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['crown height']));
			if (!empty($ch_ht) && (!is_numeric($ch_ht) || $ch_ht < 0.01 || $ch_ht > 100 || preg_match('/\.\d{3,}/', round($ch_ht, 2)))) {
				$reason .= "Crown Height, ";
				$status['success'] = false;
			}

			$ch_ag = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['crown angle']));
			if (!empty($ch_ag) && (!is_numeric($ch_ag) || $ch_ag < 0.01 || $ch_ag > 100 || preg_match('/\.\d{3,}/', round($ch_ag, 2)))) {
				$reason .= "Crown Angle, ";
				$status['success'] = false;
			}

			$pv_ht = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['pavilion depth']));
			if (!empty($pv_ht) && (!is_numeric($pv_ht) || $pv_ht < 0.01 || $pv_ht > 100 || preg_match('/\.\d{3,}/', round($pv_ht, 2)))) {
				$reason .= "Pavilion Height, ";
				$status['success'] = false;
			}
			$pv_ag = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['pavilion angle']));
			if (!empty($pv_ag) && (!is_numeric($pv_ag) || $pv_ag < 0.01 || $pv_ag > 100 || preg_match('/\.\d{3,}/', round($pv_ag, 2)))) {
				$reason .= "Pavilion Angle, ";
				$status['success'] = false;
			}
		} else {
			// $ch_ht = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['crown height']));
			// if (!empty($ch_ht) && (!is_numeric($ch_ht) || $ch_ht < 0.01 || $ch_ht > 100 || preg_match('/\.\d{3,}/', round($ch_ht, 2)))) {
			// 	$reason .= "Crown Height, ";
			// 	$status['success'] = false;
			// }

			// $ch_ag = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['crown angle']));
			// if (!empty($ch_ag) && (!is_numeric($ch_ag) || $ch_ag < 0.01 || $ch_ag > 100 || preg_match('/\.\d{3,}/', round($ch_ag, 2)))) {
			// 	$reason .= "Crown Angle, ";
			// 	$status['success'] = false;
			// }

			// $pv_ht = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['pavilion depth']));
			// if (!empty($pv_ht) && (!is_numeric($pv_ht) || $pv_ht < 0.01 || $pv_ht > 100 || preg_match('/\.\d{3,}/', round($pv_ht, 2)))) {
			// 	$reason .= "Pavilion Height, ";
			// 	$status['success'] = false;
			// }
			// $pv_ag = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['pavilion angle']));
			// if (!empty($pv_ag) && (!is_numeric($pv_ag) || $pv_ag < 0.01 || $pv_ag > 100 || preg_match('/\.\d{3,}/', round($pv_ag, 2)))) {
			// 	$reason .= "Pavilion Angle, ";
			// 	$status['success'] = false;
			// }
		}

		$countrylistarray = array(
			'in', 'india', 'surat', 'ind', 'mum',
			'mumbai', 'ncindia', 'hk', 'hongkong', 'nchk',
			'bl', 'be', 'belgium', 'bg', 'ant',
			'antwerp', 'is', 'isr', 'israel', 'us',
			'usa', 'ny', 'la', 'newyork', 'losangels',
			'unitedstatesofamerica', 'unitedstates', 'dubai', 'uae', 'china', 'uk',
			'london', 'malasiya', 'thailand', 'singapore', 'canada',
			'transit', 'upcoming', 'sz', '报关中', '', 'st','mb'
		);
		if (!in_array(trim(str_replace(array(" ",'-','.','&',','),"",strtolower(@$data['country']))),$countrylistarray)) {
			$reason .= "Country, ";
			$status['success'] = false;
		}

		$status['reason'] = $reason;
		return $status;
	}

    public static function LabGrownCondition($data, $shape)
	{
		$status = array();
		$status['success'] = true;
		$reason = '';

		if ($shape == "") {
			$reason .= "Shape, ";
			$status['success'] = false;
		}

		// if (!in_array(strtoupper(trim($data['availability'])), array('','G', 'AVAILABLE','GUARANTEED AVAILABLE', 'A', 'AV', 'GA', 'B', 'M', 'H', 'NA', 'STOCK','MEMO', 'BUSY', 'HOLD'))) {
		// 	$reason .= "Availability, ";
		// 	$status['success'] = false;
		// }

		$data['weight'] = trim(@$data['weight']);
		if ((@$data['weight'] < "0.07" && $data > "999") || !is_numeric(@$data['weight'])) {
			$reason .= "Weight, ";
			$status['success'] = false;
		} else if (preg_match('/\.\d{4,}/', @$data['weight']))
		{
			$reason .= "Carat up to 3 decimals, ";
			$status['success'] = false;
		}

		if (strtoupper(@$data['color']) == "FANCY" || @$data['color'] == "*" || @$data['color'] == "") {
			if (empty(trim(@$data['fancy color']))) {
				$reason .= "Fancy Color, ";
				$status['success'] = false;
			} else {
				$folor = strtoupper(trim(@$data['fancy color']));
				$folor = str_replace("-", " ", $folor);
				$fc = explode(" ", $folor);
				$haystack = array('BK', 'BLACK', 'B', 'BLUE', 'BN', 'BROWN', 'CH', 'CHAMELEON', 'CG', 'COGNAC', 'GY', 'GRAY', 'GREY', 'KG', 'GREEN', 'O', 'ORANGE', 'P', 'PINK', 'PL', 'PURPLE', 'R', 'RED', 'V', 'VIOLET', 'Y', 'YELLOW', 'W', 'WHITE', 'OT', 'X', 'OTHER');
				if (count(array_intersect($haystack, $fc)) != count($fc)) {
					$reason .= "Fancy Color, ";
					$status['success'] = false;
				}
			}

			if (empty(trim(@$data['fancy color intensity'])) || !in_array(strtoupper(@$data['fancy color intensity']), array('F', 'FAINT', 'VERY LIGHT', 'VL', 'LIGHT', 'L', 'FANCY LIGHT', 'FL', 'FCL', 'FANCY DARK', 'DARK', 'FCD', 'FANCY', 'FC', 'FANCY INTENSE', 'I', 'FI', 'INTENSE', 'FANCY VIVID', 'VIVID', 'V', 'FV', 'D', 'FD', 'DEEP', 'FANCY DEEP'))) {
				$reason .= "Fancy Color Intensity, ";
				$status['success'] = false;
			}

			$fovertone = strtoupper(trim(@$data['fancy color overtone']));
			$fovertone = str_replace("-", " ", $fovertone);
			$fover = explode(" ", $fovertone);
			$haystack = array('', 'NONE', 'YELLOW', 'YELLOWISH', 'PINK', 'PINKISH', 'BLUE', 'BLUISH', 'RED', 'REDDISH', 'GREEN', 'GREENISH', 'PURPLE', 'PURPLISH', 'ORANGE', 'ORANGEY', 'VIOLET', 'GRAY', 'GRAYISH', 'BLACK', 'BROWN', 'BROWNISH', 'CHAMPANGE', 'COGNAC', 'CHAMELEON', 'VIOLETISH', 'WHITE', 'OTHER');
			if (!empty(@$data['fancy color overtone']) && count(array_intersect($haystack, $fover)) != count($fover)) {
				$reason .= "Fancy Color Overtone, ";
				$status['success'] = false;
			}
		} else {
			if (!in_array(strtoupper(trim(@$data['color'])), array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'OP', 'QR', 'ST', 'UV', 'WX', 'YZ', 'O-P', 'Q-R', 'S-T', 'U-V', 'W-X', 'Y-Z'))) {
				$reason .= "Color, ";
				$status['success'] = false;
			}

			// if (!empty(trim(@$data['color'])) && (!empty(trim(@$data['fancy color'])) || !empty(trim(@$data['fancy color intensity'])) || !empty(trim(@$data['fancy color overtone'])))) {
			// 	$reason .= "Color, ";
			// 	$status['success'] = false;
			// }
		}

		if (!in_array(strtoupper(trim(str_replace(array(" ",), "", @$data['clarity']))), array('FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1', 'I2', 'I3'))) {
			$reason .= "Clarity, ";
			$status['success'] = false;
		}

		$cutarraywithblank = array(
			'','e','ex','exc','excellent','i',
			'id','ideal','f','fr','fair',
			'g','gd','good','v','vg',
			'verygood','p','pr','poor','**',
			'na','3ex','exideal'
		);
		if ($shape == "ROUND") {
			if (strtoupper(trim(@$data['color'])) != "FANCY" || trim(@$data['color']) != "*" || trim(@$data['color']) != "") {
				if (!in_array(trim(str_replace(array(' ','-','.','&',',','+'),'',strtolower(@$data['cut grade']))),$cutarraywithblank)) {
					$reason .= "Cut, ";
					$status['success'] = false;
				}
			} else {
				$cutarraywithoutblank = array(
					'e','ex','exc','excellent','i',
					'id','ideal','f','fr','fair',
					'g','gd','good','v','vg',
					'verygood','p','pr','poor','**',
					'na','3ex','exideal'
				);
				if (!in_array(trim(str_replace(array(' ','-','.','&',',','+'),'',strtolower(@$data['cut grade']))),$cutarraywithoutblank)) {
					$reason .= "Cut, ";
					$status['success'] = false;
				}
			}
		} else {
			if (!in_array(trim(str_replace(array('n/a','na','none',' ','-','.','&',',','+'),'',strtolower(@$data['cut grade']))),$cutarraywithblank)) {
				$reason .= "Cut, ";
				$status['success'] = false;
			}
		}

		if (!in_array(strtoupper(trim(@$data['polish'])), array('I', 'ID', 'IDEAL', 'EX', 'EXC', 'EXCELLENT', 'VG', 'VERY GOOD', 'G', 'GD', 'GOOD', 'F', 'FR', 'FAIR', 'P', 'PR', 'POOR'))) {
			$reason .= "Polish, ";
			$status['success'] = false;
		}
		if (!in_array(strtoupper(trim(@$data['symmetry'])), array('I', 'ID', 'IDEAL', 'EX', 'EXC', 'EXCELLENT', 'VG', 'VERY GOOD', 'G', 'GD', 'GOOD', 'F', 'FR', 'FAIR', 'P', 'PR', 'POOR'))) {
			$reason .= "Symmetry, ";
			$status['success'] = false;
		}

		$fluorescenceintensityarray  = array(
			'','n','none','non','no','nil','fl0','nn',
			'f','faint','fnt','negligible','fa','fayl',
			'fl','fl1','m','me','medium','med','fl2','md',
			'mdbl','mdyl','mb','mediumblue','s','strong','strongblue',
			'stg','st','stbl','styl','fl3','sb','vs','verystrong','vst',
			'vstb','fl4','vsl','vstg','vstrong','vstrgblue','vsl','vslg',
			'vslt','vsli','veryslight','vslight','sl','sli','slt','slg','slight',
			'str','vstr'
		);

		if (!in_array(trim(str_replace(array(' ','-','.','&',','),'',strtolower(@$data['fluorescence intensity']))),$fluorescenceintensityarray)) {
			$reason .= 'FLUORESCENCE, ';
			$status['success'] = false;
		}

        if (@$data['measurements'] != '') {
            $mesurment = str_replace(array(" ",' '), "", @$data['measurements']);
            $mesurment = str_replace(array('*', '-', 'x', "mm"), "x", strtolower($mesurment));
            $main = explode("x", $mesurment);
            $C_Length = (!empty($main[0])) ? trim($main[0]) : trim(@$data['length']);
            $C_Width = (!empty($main[1])) ? trim($main[1]) : trim(@$data['width']);
            $C_Depth = (!empty($main[2])) ? trim($main[2]) : trim(@$data['height']);
        }
        elseif (!empty($data['length']) && !empty($data['width']) && !empty($data['height'])) {
            $C_Length = @$data['length'];
            $C_Width = @$data['width'];
            $C_Depth = @$data['height'];
        }

		if (empty($C_Length) || !is_numeric($C_Length) || $C_Length < 1 || $C_Length > 100) {
			$reason .= "MEASUREMENTS LENGTH, ";
			$status['success'] = false;
		}
		if (empty($C_Width) || !is_numeric($C_Width) || $C_Width < 1 || $C_Width > 100) {
			$reason .= "MEASUREMENTS WIDTH, ";
			$status['success'] = false;
		}
		if (empty($C_Depth) || !is_numeric($C_Depth) || $C_Depth < 1 || $C_Depth > 100) {
			$reason .= "MEASUREMENTS DEPTH, ";
			$status['success'] = false;
		}

		if (!in_array(strtoupper(trim(@$data['lab'])), array('G', 'GIA', 'H', 'HRD', 'I', 'IGI', 'A', 'AGS', 'GCAL'))) {
			$reason .= "Lab, ";
			$status['success'] = false;
		}
		if (empty(trim(@$data['certificate #']))  || !is_numeric(str_replace(array("S4A", "S3G", "S3H", "LGM3C","LGM3E", "LG"), "", @$data['certificate #']))) { //!is_numeric(str_replace(array("LG","S4A"), "", @$data['Certificate #'])) || floor(@$data['Certificate #']) != @$data['Certificate #']
			$reason .= "Certificate, ";
			$status['success'] = false;
		}

		if(array_key_exists ('$/ct' , $data))
		{
			$doller_per = str_replace(array(",", "$"), "", trim(@$data['$/ct']));
		}
		else if(array_key_exists ('price$' , $data))
		{
			$doller_per = str_replace(array(",", "$"), "", trim(@$data['price$']));
		}

		if (empty($doller_per) || $doller_per < 0 || !is_numeric($doller_per)) {
			$reason .= "Doller Per carat, ";
			$status['success'] = false;
		}

		$depth = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['depth percent']));
		if (empty($depth) || !is_numeric($depth) || $depth < 0.01 || $depth > 100) {
			$reason .= "Depth, ";
			$status['success'] = false;
		}

		$table = trim(str_replace(array(" ", " ", "%", "°"), "", @$data['table percent']));
		if (empty($table) || !is_numeric($table) || $table < 0.01 || $depth > 100) {
			$reason .= "Table, ";
			$status['success'] = false;
		}

		$countrylistarray = array(
			'in', 'india', 'surat', 'ind', 'mum',
			'mumbai', 'ncindia', 'hk', 'hongkong', 'nchk',
			'bl', 'be', 'belgium', 'bg', 'ant',
			'antwerp', 'is', 'isr', 'israel', 'us',
			'usa', 'ny', 'la', 'newyork', 'losangels',
			'unitedstatesofamerica', 'unitedstates', 'dubai', 'uae', 'china', 'uk', 'unitedkingdom',
			'london', 'malasiya', 'thailand', 'singapore', 'canada',
			'transit', 'upcoming', 'sz', '报关中', '', 'st','mb'
		);
		if (!in_array(trim(str_replace(array(" ",'-','.','&',','),"",strtolower(@$data['country']))),$countrylistarray)) {
			$reason .= "Country, ";
			$status['success'] = false;
		}

		$status['reason'] = $reason;
		return $status;
	}

    public static function ShapeValidation($data)
    {
        $data = trim(str_replace(array(" ", '-', '.', '&', ','), "", strtolower($data)));

        switch ($data) {
            case 'b':
            case 'r':
            case 'br':
            case 'rb':
            case 'rd':
            case 'rbc':
            case 'roundbrilliant':
            case 'round':
            case 'rnd':
                return 'ROUND';
                break;
            case 'p':
            case 'ps':
            case 'pb':
            case 'pmb':
            case 'pe':
            case 'psh':
            case 'pear':
            case 'pearm':
            case 'pearmodifiedbrilliant':
            case 'par':
            case 'rosepear':
            case 'pearbrilliant':
                return 'PEAR';
                break;
            case 'e':
            case 'em':
            case 'ec':
            case 'emr':
            case 'emd':
            case 'emb':
            case 'emerald':
            case 'emeraldm':
            case 'emeraldb':
            case 'emeraldcut':
            case 'emraldcut':
            case 'modifiedemeraldcut':
                return 'EMERALD';
                break;
            case 'prn':
            case 'pr':
            case 'prns':
            case 'prin':
            case 'pn':
            case 'pc':
            case 'mdsqb':
            case 'smb':
            case 'squaremodified':
            case 'squaremodifiedbrilliant':
            case 'princess':
            case 'princessm':
            case 'princesscut':
            case 'pri':
                return 'PRINCESS';
                break;
            case 'marquise':
            case 'marquiseb':
            case 'marquies':
            case 'mqb':
            case 'mb':
            case 'm':
            case 'mq':
            case 'mqs':
            case 'marq':
            case 'mrq':
            case 'marquisebrilliant':
            case 'rosemarquise':
                return 'MARQUISE';
                break;
            case 'a':
            case 'css':
            case 'cssc':
            case 'ac':
            case 'as':
            case 'sqe':
            case 'seq':
            case 'sqem':
            case 'sqemerald':
            case 'sqemeraldcut':
            case 'squareemeraldcut':
            case 'squareemerald':
            case 'se':
            case 'sem':
            case 'asscher':
            case 'ashcer':
            case 'asschersquareemeraldcut':
            case 'ash':
                return 'ASSCHER';
                break;
            case 'o':
            case 'ov':
            case 'ob':
            case 'omb':
            case 'oval':
            case 'ovalm':
            case 'ovalb':
            case 'roseoval':
            case 'ovalbrilliant':
            case 'ovl':
                return 'OVAL';
                break;
            case 'heart':
            case 'h':
            case 'hb':
            case 'hs':
            case 'ht':
            case 'hrt':
            case 'he':
            case 'mhrc':
            case 'heartb':
            case 'heartbrilliant':
            case 'heartmodifiedbrilliant':
                return 'HEART';
                break;
            case 'rn':
            case 'rt':
            case 'rad':
            case 'ra':
            case 'rc':
            case 'rdn':
            case 'crb':
            case 'crmb':
            case 'rcrmb':
            case 'rcrb':
            case 'lradiant':
            case 'longradiant':
            case 'lr_brilliant':
            case 'radiant':
            case 'radiantmodified':
            case 'radiantmodifiedbrilliant':
            case 'crs':
            case 'ccrmb':
            case 'rab':
            case 'lgradiant':
            case 'cutcorneredrectangularmodifiedbrilliant':
            case 'modifiedemerald':
            case 'ccemerald':
            case 'lr':
            case 'lrad':
            case 'radiantcut':
            case '.v.l.radiant':
                return 'RADIANT';
                break;
            case 'sqradiant':
            case 'sr':
            case 'sqr':
            case 'sqra':
            case 'squareradiant':
            case 'squareradiantmodified':
                return 'SQUARE RADIANT';
                break;
            case 'cb':
            case 'cushionbrillia':
            case 'cushionbrilliant':
            case 'cubr':
            case 'c':
            case 'cs':
            case 'cux':
            case 'cu':
            case 'cun':
            case 'cush':
            case 'cus':
            case 'crsc':
            case 'crc':
            case 'csc':
            case 'cx':
            case 'rcsb':
            case 'rcmd':
            case 'scx':
            case 'sqcu':
            case 'scu':
            case 'cushion':
            case 'cushioncut':
            case 'longcush':
            case 'longcushion':
            case 'sqcushion':
            case 'sqcushionbrilliant':
            case 'squarecushion':
            case 'squarecushionbrilliant':
                return 'CUSHION';
                break;
            case 'cmb':
            case 'cm':
            case 'cmd':
            case 'ccsmb':
            case 'sqcmb':
            case 'cumbr':
            case 'csmb':
            case 'cushm':
            case 'cushionmodifie':
            case 'cushionmodified':
            case 'cushionmodifiedbrilliant':
            case 'cushionmodifiedbrillent':
            case 'cushionmodify':
            case 'cushionmod':
            case 'cutcorneredre':
            case 'cushionmbr':
            case 'cushionmb':
            case 'cmbn':
            case 'lgcmb':
            case 'cushionm':
            case 'cushionmodbrilliant':
            case 'cushionmodifiedbrillant':
            case 'sqcushionmb':
            case 'sqmcu':
            case 'squarecushionmodifiedbrilliant':
            case 'cushionsquaremodifiedbrilliant':
            case 'elongatedcushionmodifiedbrilliant':
            case 'elongatedcushionmodified':
            case 'rosecushion':
            case 'lcushionbrilliant':
            case 'cbsc':
                return 'CUSHION MODIFIED';
                break;
            case 't':
            case 'tr':
            case 'tril':
            case 'trl':
            case 'trillion':
            case 'trilliant':
            case 'trillian':
                return 'TRILLIANT';
                break;
            case 'ta':
            case 'tra':
            case 'tri':
            case 'triangle':
                return 'TRIANGLE';
                break;
            case 'baguette':
            case 'baguettes':
            case 'bg':
            case 'bag':
                return 'BAGUETTE';
                break;
            case 'fn':
            case 'rrc':
            case 'crsc':
            case 'rx':
            case 'rmb':
            case 'rcsmb':
            case 'x':
            case 'other':
            case 'others':
            case 'eu':
            case 'rr':
                return 'OTHER';
                break;
            default:
                return '';
        }
    }

    public static function CutValidation($data) {
        $dataModified = trim(str_replace(array(' ','-','.','&',',','+'),'',strtolower($data)));
        switch ($dataModified) {
            case 'e':
            case 'ex':
            case 'exc':
            case 'excellent':
            case '3ex':
            case 'exideal':
                return 'EX';
                break;
            case 'i':
            case 'id':
            case 'ideal':
                return 'ID';
                break;
            case 'f':
            case 'fr':
            case 'fair':
                return 'FR';
                break;
            case 'g':
            case 'gd':
            case 'good':
                return 'GD';
                break;
            case 'v':
            case 'vg':
            case 'verygood':
                return 'VG';
                break;
            case 'p':
            case 'pr':
            case 'poor':
                return 'PR';
                break;
            case '**':
            case 'na':
            case 'n/a':
            case 'none':
                return '';
                break;
            default:
                return $data;
        }
    }

    public static function ColorValidation($data) {
		$data = strtoupper($data);
		$data = str_replace(array('-', '+',' '), "", $data);

		if ($data == "OP" || $data == "O" || $data == "P") {
			return "OP";
		} else if ($data == "Q" || $data == "R" || $data == "QR") {
			return "QR";
		} else if ($data == "S" || $data == "T" || $data == "ST") {
			return "ST";
		} else if ($data == "U" || $data == "V" || $data == "UV") {
			return "UV";
		} else if ($data == "W" || $data == "X" || $data == "WX") {
			return "WX";
		} else if ($data == "Y" || $data == "Z" || $data == "YZ") {
			return "YZ";
		} else if ($data == "YELLOW" || $data == "GREEN" || $data == "PINK") {
			return "";
		} elseif ($data == "*" || $data == "FC" || $data == "FY" || $data == "LB") {
			return "";
		} elseif (strlen($data) > 2) {
			return "";
		} elseif ($data > "O") {
			return $data;
		} else {
			return $data;
		}
	}

    public static function FluorescenceValidation($data) {
		$dataModified = trim(str_replace(array(" ",'-','.','&',','),"",strtolower($data)));
		switch($dataModified)
		{
			case '':
			case 'n':
			case 'none':
			case 'non':
			case 'no':
			case 'nil':
			case 'fl0':
			case 'nn':
				return "NON";
				break;
			case 'f':
			case 'faint':
			case 'fnt':
			case 'negligible':
			case 'fa':
			case 'fayl':
			case 'fl':
			case 'fl1':
				return "FNT";
				break;
			case 'm':
			case 'me':
			case 'medium':
			case 'med':
			case 'fl2':
			case 'md':
			case 'mdbl':
			case 'mdyl':
			case 'mb':
			case 'mediumblue':
				return "MED";
				break;
			case 's':
			case 'strong':
			case 'strongblue':
			case 'stg':
			case 'st':
			case 'stbl':
			case 'styl':
			case 'fl3':
			case 'sb':
			case 'str':
				return "STG";
				break;
			case 'vs':
			case 'verystrong':
			case 'vst':
			case 'vstb':
			case 'fl4':
			case 'vsl':
			case 'vstg':
			case 'vstrong':
			case 'vstrgblue':
			case 'vstr':
				return "VST";
				break;
			case 'vsl':
			case 'vslg':
			case 'vslt':
			case 'vsli':
			case 'veryslight':
			case 'vslight':
				return "VSLT";
				break;
			case 'sl':
			case 'sli':
			case 'slt':
			case 'slg':
			case 'slight':
				return "SLT";
				break;
			default:
				return $data;
		}
	}

    public static function ImageValidation($data) {
		if (!empty(trim($data)) && (strpos(strtolower($data),'.jpg') !== false || strpos(strtolower($data),'.jpeg') !== false)) {
			return $data;
		}
		else
		{
			return "";
		}
	}

    public static function VideoValidation($data) {
		if (!empty(trim($data)) && (strpos($data, 'http') !== false || strpos($data, 'www.') !== false) && (strpos($data,'.jpeg') == false && strpos($data,'.jpg') == false)) {
			if(strpos($data, 'https') !== false)
			{
				return $data;
			}
			else{
				if(strpos($data, 'gem360') !== false)
				{
					return str_replace('http', 'https', $data);
				}
				else
				{
					return $data;
				}
			}
		}
		else
		{
			return "";
		}
	}

    public static function MilkyValidation($data) {
		$data = trim(str_replace(array(' ','-','.','&',','),'',strtolower($data)));
		switch($data)
		{
			case 'excellent':
			case 'ex':
			case 'id':
			case 'none':
			case 'no':
			case 'm0':
			case 'nomilky':
			case 'n':
			case 'milky0':
			case 'l1':
			case 'non':
			case 'na':
			case '':
			case 'nn':
				return "NO MILKY";
				break;
			case 'm1':
			case 'lightmilky':
			case 'nv':
			case 'light':
			case 'milky1':
			case 'gd':
			case 'g':
			case 'ml1':
			case 'good':
			case 'gd+':
			case 'vg':
			case 'l':
			case 'faint':
				return "LIGHT MILKY";
				break;
			case 'm2':
			case 'm3':
			case 'medium':
			case 'mediummilky':
			case 'milky2':
			case 'ml2':
			case 'milky':
			case 'yes':
			case 'heavymilky':
			case 'strong':
				return "MILKY";
				break;
			default:
				return '';
		}
	}

    public static function LabValidation($data) {
		$data = strtoupper($data);
		if ($data == "G" || $data == "GIA") {
			return "GIA";
		} elseif ($data == "H" || $data == "HRD") {
			return "HRD";
		} elseif ($data == "I" || $data == "IGI") {
			return "IGI";
		} elseif ($data == "A" || $data == "AGS") {
			return "AGS";
		} elseif ($data == "GCAL") {
			return "GCAL";
		} else {
			return trim($data);
		}
	}

    public static function CountryValidation($data) {
		$data = trim(str_replace(array(" ",'-','.','&',','),"",strtolower($data)));
		switch($data)
		{
			case 'in':
			case 'india':
			case 'surat':
			case 'ind':
			case 'mum':
			case 'mumbai':
			case 'ncindia':
			case 'st':
			case 'mb':
				return "INDIA";
				break;
			case 'hk':
			case 'hongkong':
			case 'nchk':
				return "HONGKONG";
				break;
			case 'is':
			case 'isr':
			case 'israel':
				return "ISRAEL";
				break;
			case 'us':
			case 'usa':
			case 'ny':
			case 'la':
			case 'newyork':
			case 'losangels':
			case 'unitedstatesofamerica':
			case 'unitedstates':
				return "USA";
				break;
			case 'dubai':
			case 'uae':
				return "UAE";
				break;
			case 'bl':
			case 'be':
			case 'belgium':
			case 'bg':
			case 'ant':
			case 'antwerp':
				return "BELGIUM";
				break;
			case 'london':
			case 'uk':
			case 'u.k':
			case 'unitedkingdom':
				return "UK";
				break;
			default:
				return "OTHER";
		}
	}

	public static function EyecleanValidation($data)
	{
		$data = strtoupper($data);
        switch($data)
		{
			case 'YES':
			case 'Y':
			case '100':
			case '100%':
			case '100% EYE CLEAN':
			case 'E1':
				return "YES";
				break;
			case 'N':
			case 'NO':
			case 'E2':
				return "NO";
				break;
			default:
				return "";
		}
	}

    public static function ClarityValidation($data) {
        $data = str_replace(array('-', '+',' '), '', $data);
        return strtoupper($data);
    }

    public static function CheckgridleThin($data) {
		if (!empty(trim($data)) && !preg_match('/^(EXTREMELY THICK|XTK|EXTHICK|EXTHK|XTHK,XTHICK|XTHIK|ETK|EK|XK|VERY THICK|VTK|VTHCK|VTHK|VTHICK|THICK|TK|THK|THIK|THIC|SLIGHTLY THICK|STK|SLTK|SLTHK|MEDIUM|M|MED|MD|THIN|TN|THN|SLIGHTLY THIN|STN|SLTN|SLTHN|VERY THIN|VTN|VTHN|VTHIN|VN|EXTREMELY THIN|XTN|XTHN|EXTN|ETN|EN|X)$/', strtoupper($data))) {
			return "";
		}
		else
		{
			return $data;
		}
	}

	public static function CheckgridleThink($data) {
		if (!empty(trim($data)) && !preg_match('/^(EXTREMELY THICK|XTK|EXTHICK|EXTHK|XTHK,XTHICK|XTHIK|ETK|EK|XK|VERY THICK|VTK|VTHCK|VTHK|VTHICK|THICK|TK|THK|THIK|THIC|SLIGHTLY THICK|STK|SLTK|SLTHK|MEDIUM|M|MED|MD|THIN|TN|THN|SLIGHTLY THIN|STN|SLTN|SLTHN|VERY THIN|VTN|VTHN|VTHIN|VN|EXTREMELY THIN|XTN|XTHN|EXTN|ETN|EN|X)$/', strtoupper($data))) {
			return "";
		}
		else
		{
			return $data;
		}
	}

    public static function GridleConValidation($data) {
		if (in_array(strtoupper(trim($data)), array('P','POLISHED'))) {
			return "POLISHED";
		}
		elseif (in_array(strtoupper(trim($data)), array('F','FACETED'))) {
			return "FACETED";
		}
		elseif (in_array(strtoupper(trim($data)), array('B','BRUTED'))) {
			return "BRUTED";
		}
		elseif (!in_array(strtoupper(trim($data)), array('', 'P','POLISHED', 'F','FACETED', 'B','BRUTED'))) {
			return "";
		}
		else
		{
			return $data;
		}
	}

	public static function CuletConValidation($data) {
		if (!in_array(strtoupper(trim($data)), array('', 'P','POINTED', 'A','ABRADED', 'C','CHIPPED'))) {
			return "";
			$status['success'] = false;
		}
		else
		{
			return $data;
		}
	}

	public static function CuletSizeValidation($data)
	{
		$data = trim(str_replace(" ", "", strtolower($data)));
		switch ($data) {
			case 'el':
			case 'extremelylarge':
				return 'Extremely Large';
				break;
			case 'vl':
			case 'verylarge':
				return 'Very Large';
				break;
			case 'l':
			case 'large':
				return 'Large';
				break;
			case 'sl':
			case 'slightlylarge':
				return 'Slightly Large';
				break;
			case 'm':
			case 'medium':
				return 'Medium';
				break;
			case 's':
			case 'small':
				return 'Small';
				break;
			case 'vs':
			case 'verysmall':
				return 'Very Small';
				break;
			case 'n':
			case 'none':
				return 'None';
				break;
			default:
				return '';
		}
	}

    public static function GridlePerValidation($data) {
		$data = str_replace('%','',$data);
		if (!empty(trim($data)) && (!is_numeric($data) || $data < 1 || preg_match('/\.\d{3,}/', $data))) {
			return "";
		}
		else
		{
			return $data;
		}
	}

    public static function AvailabilityValidation($data)
	{
		$data = trim(strtolower($data));
		$data = str_replace(" ", "", $data);
		switch ($data) {
            case '':
			case 'g':
			case 'ga':
			case 'guara':
			case 'guaranteedavailability':
			case 'guaranteed':
			case 'guaranteedavailable':
			case 'available':
            case 'availabile':
            case 'onhand':
            case 'availabal':
            case 'avaiable':
            case 'availbale':
            case 'avelebal':
            case 'availble':
            case 'availability':
			case 'stock':
            case 'instock':
			case 'a':
			case 'av':
            case 'ava':
            case 'avl':
			case 'avail':
			case 'avi':
            case 'avl':
			case 'alab':
			case 'yes':
            case 'y':
				return 'available';
				break;
            case 'm':
            case 'h':
            case 'me':
            case 'oh':
            case 'on-hold':
            case 'onhold':
            case 'onmemo':
            case 'oh':
            case 'memo':
            case 'extmemo':
                return 'hold';
                break;
            case 'n':
            case 'na':
            case 'no':
            case 's':
            case 'soldout':
                return 'sold';
                break;
            case 'lab':
                return 'lab';
                break;
			default:
				return $data;
		}
	}

    public static function TreatmentValidation($data)
	{
		$data = trim(strtolower($data));
		$data = str_replace(array(' ','-','.','&',','), "", $data);
		switch ($data) {
			case 'cvd':
			case 'cv':
			case 'n':
            case 'cvdcvdtype':
            case 'cvdcvdtypeiia':
            case 'cvdtype2a':
			case 'cvdtypella':
            case 'type2a':
            case 'type||a':
            case 'typella':
				return 'CVD';
				break;
            case 'h':
            case 'hpht':
            case 'type2':
            case 'typeii':
                return 'HPHT';
                break;
			default:
				return $data;
		}
	}

    public static function ShadeValidation($data)
	{
		$data = trim(str_replace(" ","",(strtolower($data))));
		switch($data)
		{
			case 'noshade':
			case 'white':
			case 'yellow':
			case 'none':
			case 'nobgm':
			case 'no':
			case 'wht':
				return 'none';
				break;
			case 'brown':
			case 'b-2':
			case 'b2':
			case 'br':
			case 'brn':
			case 'browntinge':
			case 'mb':
			    return 'brown';
				break;
			case 'green':
			case 'mediumgreen':
			case 'gy':
				return 'green';
				break;
			case 'faintgrey':
			case 'lightgrey':
			case 'gry':
			case 'grey':
				return 'grey';
				break;
			case 'black':
			case 'faintblack':
			case 'lightblack':
				return 'black';
				break;
			case 'pink':
			case 'lightpink':
			case 'pinkish':
			case 'l.pink':
			case 'pk':
			case 'faintpink':
				return 'pink';
				break;
			case 'blue':
			case 'lightblue':
			case 'faintblue':
			case 'blu':
				return 'blue';
				break;
			case 'faintbrown':
			case 'lightbrown':
			case 'b-1':
			case 'lb':
			case 'vlb':
			case 'b1':
			case 'ttlb':
			case 'lbr':
			case 'ybr':
			case 'fbr':
				return 'light brown';
				break;
			case 'faintgreen':
			case 'lightgreen':
			case 'vlg':
			case 'lg':
				return 'light green';
				break;
			case 'mixtinch':
			case 'mixt':
			case 'mix':
			case 'fancy':
			case 'mixtingh':
			case 'vlgy':
			case 'fnc':
			case 'mixtingh1':
				return 'mixtinge';
				break;
			default:
				if(strpos($data,'mix') !== false)
					return 'mix tinge';
				else
					return '';
		}
	}

	public static function lusterValidation($data)
	{
		$data = trim(str_replace(array(' ','-','.','&',','),'',strtolower($data)));
		switch($data)
		{
			case 'excellent':
			case 'ex':
			case 'id':
			case 'none':
			case 'no':
			case 'm0':
			case 'nomilky':
			case 'n':
			case 'milky0':
			case 'm0':
			case 'l1':
				return 'EX';
				break;
			case 'verygood':
			case 'vg':
			case 'vg+':
			case 'l2':
				return 'VG';
				break;
			case 'm1':
			case 'lightmilky':
			case 'nv':
			case 'light':
			case 'milky1':
			case 'gd':
			case 'g':
			case 'ml1':
			case 'good':
			case 'gd+':
			case 'faint':
				return 'LIGHT MILKY';
				break;
			case 'm2':
			case 'm3':
			case 'medium':
			case 'mediummilky':
			case 'milky2':
			case 'ml2':
			case 'milky':
			case 'heavymilky':
			case 'strong':
				return 'MILKY';
				break;
			default:
				return '';
		}
	}

	public static function f_color($data)
	{
		if ($data == "BK" || $data == "BLACK") {
			return "BLACK";
		} else if ($data == "B" || $data == "BLUE") {
			return "BLUE";
		} else if ($data == "BN" || $data == "BROWN") {
			return "BROWN";
		} else if ($data == "CH" || $data == "CHAMELEON") {
			return "CHAMELEON";
		} else if ($data == "CG" || $data == "COGNAC") {
			return "COGNAC";
		} else if ($data == "GY" || $data == "GRAY") {
			return "GRAY";
		} else if ($data == "KG" || $data == "GREEN") {
			return "GREEN";
		} elseif ($data == "O" || $data == "ORANGE") {
			return "ORANGE";
		} elseif ($data == "P" || $data == "PINK") {
			return "PINK";
		} elseif ($data == "PL" || $data == "PURPLE") {
			return "PURPLE";
		} elseif ($data == "R" || $data == "RED") {
			return "RED";
		} elseif ($data == "V" || $data == "VIOLET") {
			return "VIOLET";
		} elseif ($data == "Y" || $data == "YELLOW") {
			return "YELLOW";
		} elseif ($data == "W" || $data == "WHITE") {
			return "WHITE";
		} elseif ($data == "OT" || $data == "X" || $data == "OTHER") {
			return "OTHER";
		} else {
			return "OTHER";
		}
	}

	public static function fancycolorValidation($data) {
		$data = strtoupper($data);
		$data = str_replace("-", " ", $data);
		// $data = str_replace(" ", "", $data);
		$data = explode(" ", $data);
		if(count($data) == 1)
		{
			return Self::f_color($data[0]);
		}
		else if(count($data) == 2)
		{
			$d1 = Self::f_color($data[0]);
			$d2 = Self::f_color($data[1]);
			return $d1.'-'.$d2;
		}
	}

	public static function intensityValidation($data) {
		$data = strtoupper($data);
		// $data = str_replace("+", "", $data);
		// $data = str_replace("-", "", $data);
		// $data = str_replace(" ", "", $data);
		if ($data == "F" || $data == "FAINT") {
			return "FAINT";
		} else if ($data == "VL" || $data == "VERY LIGHT") {
			return "VERY LIGHT";
		} else if ($data == "FL" || $data == "FCL" || $data == "FANCY LIGHT") {
			return "FANCY LIGHT";
		} else if ($data == "FCD" || $data == "DARK" || $data == "FANCY DARK") {
			return "FANCY DARK";
		} else if ($data == "I" || $data == "FI" || $data == "INTENSE" || $data == "FANCY INTENSE") {
			return "FANCY INTENSE";
		} else if ($data == "V" || $data == "FV" || $data == "VIVID" || $data == "FANCY VIVID") {
			return "FANCY VIVID";
		} else if ($data == "D" || $data == "FD" || $data == "DEEP" || $data == "FANCY DEEP") {
			return "FANCY DEEP";
		} else if ($data == "FANCY" || $data == "FC") {
			return "FANCY";
		} else {
			return $data;
		}
	}

	public static function overtoneValidation($data) {
		$data = strtoupper($data);
		// $data = str_replace("+", "", $data);
		$data = str_replace(" ", "-", $data);
		// $data = str_replace(" ", "", $data);
		if ($data == "NONE") {
			return "";
		} else {
			return $data;
		}
	}

    public static function Diamond_CH_CA_PD_PA($data)
	{
		if (!is_numeric($data) || $data < 0.01 || $data > 100 || preg_match('/\.\d{3,}/', round($data, 2))) {
			return 0;
		} else {
			return ($data > 0.01 && $data < 0.99) ? $data * 100 : $data;
		}
	}

    public static function convert_number_to_words($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public static function shipingPriceArray()
	{
        $shiping_price_array = array();
		$shiping_price = DB::table('shipping_price')->select('*')->get();
        if (!empty($shiping_price)) {
            foreach ($shiping_price as $s_price_row) {
                $shiping_price_array[$s_price_row->location][$s_price_row->min_range][$s_price_row->max_range] = $s_price_row->pricechange;
            }
        }

        return $shiping_price_array;
	}

    public static function sPriceArray($supplier)
	{
		$s_price_array = array();
        $record = DB::table('supplier_markup')->where('supplier_id', $supplier->sup_id)->get();
        if (!empty($record)) {
            foreach ($record as $price_row) {
                $s_price_array[$price_row->shape][$price_row->min_range][$price_row->max_range] = $price_row->pricechange;
            }
        }
        return $s_price_array;
	}

    public static function findAdditionalValue($s_price_array, $carat)
    {
        foreach ($s_price_array as $key => $value) {
            if ($key <= $carat && key($value) >= $carat) {
                return reset($value);
            }
        }
    }

    public static function shippingPrice($shiping_price_array, $newdollerpercarat) {
		foreach ($shiping_price_array as $key => $value) {
			if ($key <= $newdollerpercarat && key($value) >= $newdollerpercarat) {
				return reset($value);
			}
		}
	}

        public static function procurmentPrice($supplier_price) {
		if($supplier_price <= 1000)
        {
            $procurment_price = $supplier_price + 25;
        }
        else if($supplier_price >= 7000)
        {
            $procurment_price = $supplier_price + 140;
        }
        else if($supplier_price > 1000 && $supplier_price < 7000)
        {
            $procurment_price = $supplier_price + ((2 / 100) * $supplier_price);
        }
        return $procurment_price;
	}

        public static function setNotification($customer_id,$title,$body,$date){
            $result = Notification::insert(['user_id'=>$customer_id,'title'=>$title,'body'=>$body,'created_date'=>$date,'created_by' => auth::user()->id]);

            if($result == true){
                $return = 'success';
            }
            else{
                $return = 'failed';
            }
            return $return;
        }

    public static function userPermission($url){

        $permission = UserHasPermission::whereHas('permission',function($query) use($url) {
                                            $query->where('user_id','=',auth::user()->id);
                                            $query->where('url','=',$url);
                                        })->first();

        return $permission;
    }

    public static function Whatsapp_message($to,$template,$variables = null){

        $post_data = array('messaging_product' => 'whatsapp', 'to' => $to, 'type' => 'template' , 'template' => array('name' => $template, 'language' => array('code' => 'en')));

        $parm_json = '';
        if(!empty($variables)){
            $parm = array();
            foreach($variables as $variable){
                $parm[] = array("type" => "text",'text' => $variable);
            }
            $post_data['template']['components'][] = array("type" => "body",'parameters' => $parm);
        }
        $post_field = json_encode($post_data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v15.0/113952738285545/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"messaging_product\": \"whatsapp\", \"to\": \"$to\", \"type\": \"template\", \"template\": { \"name\": \"$template\", \"language\": { \"code\": \"en\" } , \"components\" : [{ \"type\" : \"body\",\"parameters\" : [{ \"type\" : \"text\",\"text\" : \"name\" },{ \"type\" : \"text\",\"text\" : \"123456\" }] }] } }");


        $headers = array();
        $headers[] = 'Authorization: Bearer EAAIT7Bxscp4BACZBXxSIr8rvXadw6rhljxW2yXXDVL4HTx0gZCL4WFp64oR58JWFoDVZBqR8eWySyQxuBN9pvdLAeQbt2FOgPFJuGmjrNHS3AJk8Q7WUU3A5ACZCQTfVhOZBvTwmZARGhJHtufFwxFGEAPU17itWEdjt8KKIJGIkh1ZBsV8gEJ2slxd03P5YhZA9hNzlQhNyrl5aZArRGMBiv';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        return 'success';
    }

}
