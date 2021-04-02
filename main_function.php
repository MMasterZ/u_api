<?php
// Check country return true or false
function checkCountry($data){
    $notCountry = ['sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld', 'RoW'];
    if(in_array($data, $notCountry)){
        return false;
    } else {
        return true;
    }
}
?>