<?php function country_list($iso){
    if($iso == "enea"){
        return ['HKG','KOR','MNG','JPN','TWN','CHN'];
    } else if($iso == "sea"){
        return ['BRN','KHM','IDN','LAO','MYS','PHY','SGP','THA'];
    } else if($iso == "sswa"){
        return ['BGD','BTN','IND','MDV','NPL','PAK','LKA','TUR'];
    }  else if($iso == "nca"){
        return ['KAZ','KGZ','RUS'];
    } else if($iso == "pac"){
        return ['AUS','FJI'];
    } else if($iso == "lac"){
        return ['ARG','BOL','BRA','CHL','COL','ECU','MEX','PRY','PER','RoLAC','URY','VEN'];
    } else if($iso == "apta"){
        return ['BGD','IND','KOR','LAO','MNG','CHN','LKA'];
    } else if($iso == "saarc"){
        return ['BGD','BTN','IND','NPL','PAK','LKA'];
    } else if($iso == "mercosur"){
        return ['ARG','BOL','BRA','CHL','COL','ECU','PRY','PER','URY','VEN'];
    } else if($iso == "cptpp"){
        return ['AUS','BRN','CAN','CHL','JPN','MYS','MEX','PER','SGP','VNM'];
    }else if($iso == "rcep"){
        return ['AUS','BRN','CAN','CHL','JPN','MYS','MEX','PER','SGP','VNM'];
    } else if($iso == "pac_alliance"){
        return ['CHL','COL','MEX','PER'];
    } else if($iso == "bimstec"){
        return ['BGD','BTN','IND','NPL','LKA','THA'];
    } else if($iso == "ap"){
        return ['AUS','IND','JPN','KOR','CHN','RUS','SGP','TWN','THA','VNM'];
    } else if($iso == "ap"){
        return ['BGD','BTN','IND','NPL','LKA','THA'];
    } else if($iso == "euz"){
        return ['AUT','BEL','FRA','DEU','IRL','ITA','NLD','POL','ESP','SWE'];
    } else if($iso == "eur"){
        return ['BEL','FRA','DEU','IRL','ITA','NLD','POL','ESP','CHE','GBR'];
    } else if($iso == 'apec'){
         return ['AUS','CAN','JPN','KOR','MEX','CHN','POL','RUS','SGP','TWN','USA'];
    } else if($iso == 'fealac'){
         return ['AUS','BRA','JPN','KOR','MYS','MEX','CHN','SGP','THA','VNM'];
    } else if($iso == 'wld'){
         return ['CAN','FRA','DEU','ITA','JPN','KOR','NLD','CHN','GBR','USA'];
    }
}
?>