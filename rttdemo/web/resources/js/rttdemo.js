/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function limitText(limitField, limitCount, limitNum) {
    
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}