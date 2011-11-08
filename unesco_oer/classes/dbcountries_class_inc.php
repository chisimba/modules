<?php

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class dbcountries extends dbtable {

    public $options = array();

    function init() {
        parent::init("tbl_unesco_oer_countries");
        $this->options["AF"] = "Afghanistan";
        $this->options["AX"] = "Åland Islands";
        $this->options["AL"] = "Albania ";
        $this->options["DZ"] = "Algeria ";
        $this->options["AS"] = "American Samoa";
        $this->options["AD"] = "Andorra";
        $this->options["AO"] = "Angola ";
        $this->options["AI"] = "Anguilla ";
        $this->options["AQ"] = "Antarctica ";
        $this->options["AG"] = "Antigua and Barbuda ";
        $this->options["AR"] = "Argentina";
        $this->options["AU"] = "Australia ";
        $this->options["AT"] = "Austria ";
        $this->options["AZ"] = "Azerbaijan";
        $this->options["BS"] = "Bahamas ";
        $this->options["BH"] = "Bahrain ";
        $this->options["BD"] = "Bangladesh ";
        $this->options["BB"] = "Barbados ";
        $this->options["BY"] = "Belarus";
        $this->options["BE"] = "Belgium ";
        $this->options["BZ"] = "Belize ";
        $this->options["BJ"] = "Benin ";
        $this->options["BM"] = "Bermuda ";
        $this->options["BT"] = "Bhutan";
        $this->options["BO"] = "Bolivia ";
        $this->options["BA"] = "Bosnia and Herzegovina ";
        $this->options["BW"] = "Botswana ";
        $this->options["BV"] = "Bouvet Island ";
        $this->options["BR"] = "Brazil";
        $this->options["IO"] = "British Indian Ocean Territory ";
        $this->options["BN"] = "Brunei Darussalam ";
        $this->options["BG"] = "Bulgaria ";
        $this->options["BF"] = "Burkina Faso ";
        $this->options["BI"] = "Burundi";
        $this->options["KH"] = "Cambodia ";
        $this->options["CM"] = "Cameroon ";
        $this->options["CA"] = "Canada ";
        $this->options["CV"] = "Cape Verde ";
        $this->options["KY"] = "Cayman Islands ";
        $this->options["CF"] = "Central African Republic ";
        $this->options["TD"] = "Chad ";
        $this->options["CL"] = "Chile ";
        $this->options["CN"] = "China ";
        $this->options["CX"] = "Christmas Island";
        $this->options["CC"] = "Cocos (Keeling) Islands ";
        $this->options["CO"] = "Colombia ";
        $this->options["KM"] = "Comoros ";
        $this->options["CG"] = "Congo ";
        $this->options["CD"] = "Congo, the Democratic Republic of the";
        $this->options["CK"] = "Cook Islands ";
        $this->options["CR"] = "Costa Rica ";
        $this->options["CI"] = "Côte D'Ivoire";
        $this->options["HR"] = "Croatia ";
        $this->options["CU"] = "Cuba ";
        $this->options["CY"] = "Cyprus ";
        $this->options["CZ"] = "Czech Republic ";
        $this->options["DK"] = "Denmark";
        $this->options["DJ"] = "Djibouti ";
        $this->options["DM"] = "Dominica ";
        $this->options["DO"] = "Dominican Republic ";
        $this->options["EC"] = "Ecuador ";
        $this->options["EG"] = "Egypt";
        $this->options["SV"] = "El Salvador ";
        $this->options["GQ"] = "Equatorial Guinea ";
        $this->options["ER"] = "Eritrea ";
        $this->options["EE"] = "Estonia ";
        $this->options["ET"] = "Ethiopia ";
        $this->options["FK"] = "Falkland Islands (Malvinas) ";
        $this->options["FO"] = "Faroe Islands ";
        $this->options["FJ"] = "Fiji ";
        $this->options["FI"] = "Finland ";
        $this->options["FR"] = "France";
        $this->options["GF"] = "French Guiana ";
        $this->options["PF"] = "French Polynesia ";
        $this->options["TF"] = "French Southern Territories ";
        $this->options["GA"] = "Gabon ";
        $this->options["GM"] = "Gambia ";
        $this->options["GE"] = "Georgia ";
        $this->options["DE"] = "Germany ";
        $this->options["GH"] = "Ghana ";
        $this->options["GI"] = "Gibraltar ";
        $this->options["GR"] = "Greece";
        $this->options["GL"] = "Greenland ";
        $this->options["GD"] = "Grenada ";
        $this->options["GP"] = "Guadeloupe ";
        $this->options["GU"] = "Guam ";
        $this->options["GT"] = "Guatemala";
        $this->options["GG"] = "Guernsey ";
        $this->options["GN"] = "Guinea ";
        $this->options["GW"] = "Guinea-Bissau ";
        $this->options["GY"] = "Guyana ";
        $this->options["HT"] = "Haiti";
        $this->options["HM"] = "Heard Island and Mcdonald Islands ";
        $this->options["VA"] = "Holy See (Vatican City State) ";
        $this->options["HN"] = "Honduras ";
        $this->options["HK"] = "Hong Kong ";
        $this->options["HU"] = "Hungary";
        $this->options["IS"] = "Iceland ";
        $this->options["IN"] = "India ";
        $this->options["ID"] = "Indonesia ";
        $this->options["IR"] = "Iran, Islamic Republic of ";
        $this->options["IQ"] = "Iraq ";
        $this->options["IE"] = "Ireland ";
        $this->options["IM"] = "Isle of Man ";
        $this->options["IL"] = "Israel ";
        $this->options["IT"] = "Italy ";
        $this->options["JM"] = "Jamaica";
        $this->options["JP"] = "Japan ";
        $this->options["JE"] = "Jersey ";
        $this->options["JO"] = "Jordan ";
        $this->options["KZ"] = "Kazakhstan ";
        $this->options["KE"] = "KENYA";
        $this->options["KI"] = "Kiribati ";
        $this->options["KP"] = "Korea, Democratic People's Republic of ";
        $this->options["KR"] = "Korea, Republic of ";
        $this->options["KW"] = "Kuwait ";
        $this->options["KG"] = "Kyrgyzstan";
        $this->options["LA"] = "Lao People's Democratic Republic ";
        $this->options["LV"] = "Latvia ";
        $this->options["LB"] = "Lebanon ";
        $this->options["LS"] = "Lesotho ";
        $this->options["LR"] = "Liberia";
        $this->options["LY"] = "Libyan Arab Jamahiriya ";
        $this->options["LI"] = "Liechtenstein ";
        $this->options["LT"] = "Lithuania ";
        $this->options["LU"] = "Luxembourg ";
        $this->options["MO"] = "Macao";
        $this->options["MK"] = "Macedonia, the Former Yugoslav Republic of ";
        $this->options["MG"] = "Madagascar ";
        $this->options["MW"] = "Malawi ";
        $this->options["MY"] = "Malaysia ";
        $this->options["MV"] = "Maldives";
        $this->options["ML"] = "Mali ";
        $this->options["MT"] = "Malta ";
        $this->options["MH"] = "Marshall Islands ";
        $this->options["MQ"] = "Martinique ";
        $this->options["MR"] = "Mauritania";
        $this->options["MU"] = "Mauritius ";
        $this->options["YT"] = "Mayotte ";
        $this->options["MX"] = "Mexico ";
        $this->options["FM"] = "Micronesia, Federated States of ";
        $this->options["MD"] = "Moldova, Republic of";
        $this->options["MC"] = "Monaco ";
        $this->options["MN"] = "Mongolia ";
        $this->options["ME"] = "Montenegro ";
        $this->options["MS"] = "Montserrat ";
        $this->options["MA"] = "Morocco";
        $this->options["MZ"] = "Mozambique ";
        $this->options["MM"] = "Myanmar ";
        $this->options["NA"] = "Namibia ";
        $this->options["NR"] = "Nauru ";
        $this->options["NP"] = "Nepal";
        $this->options["NL"] = "Netherlands ";
        $this->options["AN"] = "Netherlands Antilles ";
        $this->options["NC"] = "New Caledonia ";
        $this->options["NZ"] = "New Zealand ";
        $this->options["NI"] = "Nicaragua";
        $this->options["NE"] = "Niger ";
        $this->options["NG"] = "Nigeria ";
        $this->options["NU"] = "Niue ";
        $this->options["NF"] = "Norfolk Island ";
        $this->options["MP"] = "Northern Mariana Islands";
        $this->options["NO"] = "Norway ";
        $this->options["OM"] = "Oman ";
        $this->options["PK"] = "Pakistan ";
        $this->options["PW"] = "Palau ";
        $this->options["PS"] = "Palestinian Territory, Occupied";
        $this->options["PA"] = "Panama ";
        $this->options["PG"] = "Papua New Guinea ";
        $this->options["PY"] = "Paraguay ";
        $this->options["PE"] = "Peru ";
        $this->options["PH"] = "Philippines";
        $this->options["PN"] = "Pitcairn ";
        $this->options["PL"] = "Poland ";
        $this->options["PT"] = "Portugal ";
        $this->options["PR"] = "Puerto Rico ";
        $this->options["QA"] = "Qatar";
        $this->options["RE"] = "Réunion ";
        $this->options["RO"] = "Romania ";
        $this->options["RU"] = "Russian Federation ";
        $this->options["RW"] = "Rwanda ";
        $this->options["SH"] = "Saint Helena";
        $this->options["KN"] = "Saint Kitts and Nevis ";
        $this->options["LC"] = "Saint Lucia ";
        $this->options["PM"] = "Saint Pierre and Miquelon ";
        $this->options["VC"] = "Saint Vincent and the Grenadines ";
        $this->options["WS"] = "Samoa";
        $this->options["SM"] = "San Marino ";
        $this->options["ST"] = "Sao Tome and Principe ";
        $this->options["SA"] = "Saudi Arabia ";
        $this->options["SN"] = "Senegal ";
        $this->options["RS"] = "Serbia";
        $this->options["SC"] = "Seychelles ";
        $this->options["SL"] = "Sierra Leone ";
        $this->options["SG"] = "Singapore ";
        $this->options["SK"] = "Slovakia ";
        $this->options["SI"] = "Slovenia";
        $this->options["SB"] = "Solomon Islands ";
        $this->options["SO"] = "Somalia ";
        $this->options["ZA"] = "South Africa ";
        $this->options["GS"] = "South Georgia and the South Sandwich Islands ";
        $this->options["ES"] = "Spain";
        $this->options["LK"] = "Sri Lanka ";
        $this->options["SD"] = "Sudan ";
        $this->options["SR"] = "Suriname ";
        $this->options["SJ"] = "Svalbard and Jan Mayen ";
        $this->options["SZ"] = "Swaziland";
        $this->options["SE"] = "Sweden ";
        $this->options["CH"] = "Switzerland ";
        $this->options["SY"] = "Syrian Arab Republic ";
        $this->options["TW"] = "Taiwan, Province of China ";
        $this->options["TJ"] = "Tajikistan";
        $this->options["TZ"] = "Tanzania, United Republic of ";
        $this->options["TH"] = "Thailand ";
        $this->options["TL"] = "Timor-Leste ";
        $this->options["TG"] = "Togo ";
        $this->options["TK"] = "Tokelau";
        $this->options["TO"] = "Tonga ";
        $this->options["TT"] = "Trinidad and Tobago ";
        $this->options["TN"] = "Tunisia ";
        $this->options["TR"] = "Turkey ";
        $this->options["TM"] = "Turkmenistan";
        $this->options["TC"] = "Turks and Caicos Islands ";
        $this->options["TV"] = "Tuvalu ";
        $this->options["UG"] = "Uganda ";
        $this->options["UA"] = "Ukraine ";
        $this->options["AE"] = "United Arab Emirates";
        $this->options["GB"] = "United Kingdom ";
        $this->options["US"] = "United States ";
        $this->options["UM"] = "United States Minor Outlying Islands ";
        $this->options["UY"] = "Uruguay ";
        $this->options["UZ"] = "Uzbekistan";
        $this->options["VU"] = "Vanuatu ";
        $this->options["VA"] = "Vatican City State ";
        $this->options["VE"] = "Venezuela ";
        $this->options["VN"] = "Viet Nam ";
        $this->options["VG"] = "Virgin Islands, British ";
        $this->options["VI"] = "Virgin Islands, U.S. ";
        $this->options["WF"] = "Wallis and Futuna";
        $this->options["EH"] = "Western Sahara ";
        $this->options["YE"] = "Yemen ";
        $this->options["CD"] = "Zaire ";
        $this->options["ZM"] = "Zambia ";
        $this->options["ZW"] = "Zimbabwe ";
    }

    /**
     * Returns an array with all countries
     * @return <Array<Institution>>
     */
    function getAllCountries() {
        $sql = "SELECT * FROM tbl_unesco_oer_countries";
        return $this->getArray($sql);
    }

    function getCountryName($countryId) {
        $sql = "SELECT * FROM tbl_unesco_oer_countries WHERE id='$countryId'";
        $countryName = $this->getArray($sql);
        return $countryName[0]['countryname'];
    }

    function getCountryByCode($code){
        foreach($this->options as $ccode=>$desc){
           
            if($ccode == $code){
                return $desc;
            }
        }
        return null;
    }
}

?>