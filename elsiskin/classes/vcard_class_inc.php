<?

/*
 * Adopted from
 * PHP vCard class v2.0
 * (c) Kai Blankenhorn
 * www.bitfolge.de/en
 * kaib@bitfolge.de
 *
 * A class to help with the generation/display and dowloading of vcards
 *
 * PHP version 5
 *
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
 *
 * @category  Chisimba
 * @package   elsiskin
 * @author    Nguni Phakela nonkululeko.phakela@wits.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: vcard_class_inc.php,v 1.1 2011-02-08 09:13:27 nguni52 Exp $
 * @link      http://avoir.uwc.ac.za
 *
 *
 *
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class vcard extends object {

    private $properties;
    private $filename;

    public function setPhoneNumber($number, $type="") {
        // type may be PREF | WORK | HOME | VOICE | FAX | MSG | CELL | PAGER | BBS | CAR | MODEM | ISDN | VIDEO or any senseful combination, e.g. "PREF;WORK;VOICE"
        $key = "TEL";
        if ($type != "") {
            $key .= ";" . $type;
        }
        $key.= ";ENCODING=QUOTED-PRINTABLE";
        $this->properties[$key] = quoted_printable_encode($number);
    }

    // UNTESTED !!!
    public function setPhoto($type, $photo) { // $type = "GIF" | "JPEG"
        $this->properties["PHOTO;TYPE=$type;ENCODING=BASE64"] = base64_encode($photo);
    }

    public function setFormattedName($name) {
        $this->properties["FN"] = quoted_printable_encode($name);
    }

    public function setName($family="", $first="", $additional="", $prefix="", $suffix="") {
        $this->properties["N"] = "$family;$first;$additional;$prefix;$suffix";
        $this->filename = "$first%20$family.vcf";
        if ($this->properties["FN"] == "")
            $this->setFormattedName(trim("$prefix $first $additional $family $suffix"));
    }

    public function setBirthday($date) { // $date format is YYYY-MM-DD
        $this->properties["BDAY"] = $date;
    }

    public function setAddress($postoffice="", $extended="", $street="", $city="", $region="", $zip="", $country="", $type="HOME;POSTAL") {
        // $type may be DOM | INTL | POSTAL | PARCEL | HOME | WORK or any combination of these: e.g. "WORK;PARCEL;POSTAL"
        $key = "ADR";
        if ($type != "")
            $key.= ";$type";
        $key.= ";ENCODING=QUOTED-PRINTABLE";
        $this->properties[$key] = encode($name) . ";" . encode($extended) . ";" . encode($street) . ";" . encode($city) . ";" . encode($region) . ";" . encode($zip) . ";" . encode($country);

        if ($this->properties["LABEL;$type;ENCODING=QUOTED-PRINTABLE"] == "") {
            //$this->setLabel($postoffice, $extended, $street, $city, $region, $zip, $country, $type);
        }
    }

    public function setLabel($postoffice="", $extended="", $street="", $city="", $region="", $zip="", $country="", $type="HOME;POSTAL") {
        $label = "";
        if ($postoffice != "")
            $label.= "$postoffice";
        if ($extended != "")
            $label.= "$extended";
        if ($street != "")
            $label.= "$street";
        if ($zip != "")
            $label.= "$zip ";
        if ($city != "")
            $label.= "$city";
        if ($region != "")
            $label.= "$region";
        if ($country != "")
            $country.= "$country";

        $this->properties["LABEL;$type;ENCODING=QUOTED-PRINTABLE"] = quoted_printable_encode($label);
    }

    public function setEmail($address) {
        $this->properties["EMAIL;INTERNET"] = $address;
    }

    public function setNote($note) {
        $this->properties["NOTE;ENCODING=QUOTED-PRINTABLE"] = quoted_printable_encode($note);
    }

    public function setURL($url, $type="") {
        // $type may be WORK | HOME
        $key = "URL";
        if ($type != "")
            $key.= ";$type";
        $this->properties[$key] = $url;
    }

    public function getVCard() {
        $text = "BEGIN:VCARD";
        $text.= "VERSION:2.1";
        foreach ($this->properties as $key => $value) {
            $text.= "$key:$value";
        }
        $text.= "REV:" . date("Y-m-d") . "T" . date("H:i:s") . "Z";
        $text.= "MAILER:PHP vCard class by Nguni Phakela adopted from Kai Blankenhorn";
        $text.= "END:VCARD";
        return $text;
    }

    public function getFileName() {
        return $this->filename;
    }

}

?>