function adjustRooms(index) {
    alert(index+1);
   
}

function windowLoad(url) {
    new Ajax.Autocompleter("input_searchStr", "autocomplete_choices", url, {minChars: 3});
}