var tmpPanes = {};
var language = {};
var glob_general_settings = {};
function GeneralSettings(){
	var response = "";
	$.ajax({
				url: './script/getGeneralSettings.php',
				method: 'POST',
				data: {response: response},
				dataType: 'json',
				success: function(res) {
					var html='';
					var buttons = {};
					buttons[language['lang']['general_settings_button']['save']] = function () {edit_general_settings();}
					buttons[language['lang']['general_settings_button']['cancel']] = function () {$(this).dialog("close");}

					var panes = {};
					panes[language['lang']['general_settings_panes']['general_graphics_options']] = "panes_general_graphics_options";
					panes[language['lang']['general_settings_panes']['general_settings_options']] = "panes_general_settings_options";
					panes[language['lang']['general_settings_panes']['general_info']] = "panes_general_info";

					var languages ="";
					for (var available_language in res['available_languages']) {
						languages += "<option>" + res['available_languages'][available_language] + "</option>";
					}
					var themes ="";
					for (var available_themes in res['available_themes']) {
						themes += "<option>" + res['available_themes'][available_themes] + "</option>";
					}

					html += "<div id='settings_panes'></div>";
					html += "<div id='panes_general_graphics_options'>";
					html += 	"<table>";
					html += 		"<tr><td>"+language['lang']['general_settings']['language']+":</td><td><select class='select-selected' id='select_language'>"+languages+"</select></td></tr>";
					html += 		"<tr><td>"+language['lang']['general_settings']['theme']+":</td><td><select class='select-selected' id='select_theme'>"+themes+"</select></td></tr>";
					html += 		"<tr><td>"+language['lang']['general_settings']['show_big_title']+":</td><td><input id='admin_show_big_title' type='checkbox' /></td></tr>";
					html += 	"</table>";
					html +=	"</div>";

					html += "<div id='panes_general_settings_options'>";
					html += 	"<table>";
					html += 		"<tr><td>"+language['lang']['general_settings']['show_always_private']+":</td><td><input id='admin_private' type='checkbox' /></td></tr>";
					html += 		"<tr><td>"+language['lang']['general_settings']['enable_album_settings']+":</td><td><input id='admin_show_album_settings' type='checkbox' /></td></tr>";
					html += 		"<tr><td>"+language['lang']['general_settings']['enable_picture_settings']+":</td><td><input id='admin_show_picture_settings' type='checkbox' /></td></tr>";
					html += 	"</table>";
					html +=	"</div>";

					html += "<div id='panes_general_info'>";
					html += 	"<table>";
					html += 		"<tr><td>"+language['lang']['general_settings']['album_number']+":</td><td>"+res['album_number']+"</td></tr>";
					html += 		"<tr><td>"+language['lang']['general_settings']['pictures_number']+":</td><td>"+res['pictures_number']+"</td></tr>";
					html += 	"</table>";
					html +=	"</div>";

					CreateDialog(language['lang']['general_settings']['dialog_title'], html, "35%", buttons, panes, res);
					$("#select_language").val(res['language']['language']);
					$("#select_theme").val(res['theme']['theme']);
					if (res['admin_private']['always_show'] == true){$('#admin_private').prop("checked", true);}
					if (res['admin_show_album_settings']['active'] == true){$('#admin_show_album_settings').prop("checked", true);}
					if (res['admin_show_picture_settings']['active'] == true){$('#admin_show_picture_settings').prop("checked", true);}
					if (res['admin_show_big_title']['active'] == true){$('#admin_show_big_title').prop("checked", true);}
				}
		});
}

function PictureSettings(id_picture){
	$.ajax({
				url: './script/getPictureSettings.php',
				method: 'POST',
				data: {id_picture: id_picture},
				dataType: 'json',
				success: function(res) {
						var checked_private_picture = "";
						var checked_visible_picture = "";
						var checked_favorites_picture = "";
						var panes = {};
						panes[language['lang']['picture_settings_panes']['picture_options']] = "panes_picture_option";
						panes[language['lang']['picture_settings_panes']['picture_info']] = "panes_picture_info";

						var buttons = {};
						buttons[language['lang']['picture_settings_button']['save']] = function () {edit_picture_settings(res['id_picture']);}
						buttons[language['lang']['picture_settings_button']['cancel']] = function () {$(this).dialog("close");}

						html = "<div id='settings_panes'></div>";
						html += "<div id='panes_picture_info'><table>";
						html += "<tr><td>"+language['lang']['picture_settings']['album_name']+":</td><td>"+res['name']+"</td></tr>";
						html += "<tr><td>"+language['lang']['picture_settings']['file_name']+":</td><td>"+res['picture_name']+"</td></tr>";
						html += "<tr><td>"+language['lang']['picture_settings']['path']+":</td><td>"+res['path']+"</td></tr>";
						html += "<tr><td>"+language['lang']['picture_settings']['folder']+":</td><td>"+res['folder_name']+"</td></tr>";
						html += "</table></div><div id='panes_picture_option'><table>";
						html += "<tr>";
						if (res['private'] == true){checked_private_picture = "checked";}
						html += "<td>"+language['lang']['picture_settings']['private']+":</td><td><input id='picture_settings_private' type='checkbox' "+checked_private_picture+" /></td>";
						html += "</tr>";
						html += "<tr>";
						if (res['visible'] == true){checked_visible_picture = "checked";}
						html += "<td>"+language['lang']['picture_settings']['visible']+":</td><td><input id='picture_settings_visible' type='checkbox' "+checked_visible_picture+" /></td>";
						html += "</tr>";
						var check_cover_image = ((res['cover_image'] == res['picture_name']) ? 'checked' : '');
						html += "<tr><td>"+language['lang']['picture_settings']['set_cover']+":</td><td><input id='picture_settings_cover_image' type='checkbox' "+check_cover_image+" /></td></tr>";
						html += "<tr>";
						if (res['favorites'] == true){checked_favorites_picture = "checked";}
						html += "<td>"+language['lang']['picture_settings']['favorites']+":</td><td><input id='picture_settings_favorites' type='checkbox' "+checked_favorites_picture+" /></td>";
						html += "</tr>";
						html += "</table></div>";
						CreateDialog(language['lang']['picture_settings']['dialog_title'], html, "35%", buttons, panes, res);
				}
		});
}

function AlbumSettings(album_id){
	$.ajax({
        url: './script/getAlbumSettings.php',
				method: 'POST',
				data: {album_id: album_id},
				dataType: 'json',
				success: function(res) {
						var checked_private_album = "";
						var only_private_album ="";
						var panes = {};
						panes[language['lang']['albums_settings_panes']['album_options']] = "panes_album_option";
						panes[language['lang']['albums_settings_panes']['album_info']] = "panes_album_info";
						var buttons = {};
						buttons[language['lang']['albums_settings_button']['save']] = function () {edit_album_settings(res['id']);}
						buttons[language['lang']['albums_settings_button']['cancel']] = function () {$(this).dialog("close");}

						html = "<div id='settings_panes'></div>";
						html += "<div id='panes_album_info'><table id='tb_settings_album_infos'>";
						html += "<tr><td>"+language['lang']['albums_settings']['folder_path']+":</td><td>"+res['path']+"</td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['folder_name']+":</td><td>"+res['folder_name']+"</td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['pictures_number']+":</td><td>"+res['picture_number']+"</td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['public_pictures_number']+":</td><td>"+res['picture_public_number']+"</td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['private_pictures_number']+":</td><td>"+res['picture_private_number']+"</td></tr>";
						html += "</table></div><div id='panes_album_option'><table name='album_options'>";
						html += "<tr><td>"+language['lang']['albums_settings']['cover']+":</td><td><input id='album_settings_album_cover' type='text' value='"+res['cover_image']+"' />";
						html += 	"<div id='clear_album_settings_cover_image' class='clear_field img_small display_img_inline' title='"+language['lang']['albums_settings_title']['cover']+"'></div></td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['album_name']+":</td><td><input id='album_settings_album_name' type='text' value='"+res['name']+"' /></td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['start_date']+":</td><td><input id='album_settings_start_date' type='text' value='"+res['start_date']+"' />";
						html += 	"<div id='clear_album_settings_start_date' class='clear_field img_small display_img_inline' title='"+language['lang']['albums_settings_title']['start_date']+"'></div></td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['end_date']+":</td><td><input id='album_settings_end_date' type='text' value='"+res['end_date']+"' />";
						html += 	"<div id='clear_album_settings_end_date' class='clear_field img_small display_img_inline' src='./img/delete.png' title='"+language['lang']['albums_settings_title']['end_date']+"'></div></td></tr>";
						if (res['album_private'] == true){checked_private_album = "checked";}
						if (res['show_only_private'] == true){only_private_album = "checked";}
						html += "<tr><td>"+language['lang']['albums_settings']['show_private']+":</td><td><input id='album_settings_private' type='checkbox' "+checked_private_album+"/></td></tr>";
						html += "<tr><td>"+language['lang']['albums_settings']['show_only_private']+":</td><td><input id='album_settings_only_private' type='checkbox' "+only_private_album+"/></td></tr>";
						html += "</table></div>";
						html += "<div><input id='delete_album' type='button' value='"+language['lang']['albums_settings_button']['delete_album']+"' onClick='deleteAlbum("+album_id+")'/></div>";

						CreateDialog(language['lang']['albums_settings']['dialog_title'], html, "34%", buttons, panes);
						$("#album_settings_start_date").datepicker({dateFormat:"yy-mm-dd"});
						$("#album_settings_end_date").datepicker({dateFormat:"yy-mm-dd"});
						$("#clear_album_settings_start_date").on("click", function(){$('#album_settings_start_date').val('0000-00-00');});
						$("#clear_album_settings_end_date").on("click", function(){$('#album_settings_end_date').val('0000-00-00');});
						$("#clear_album_settings_cover_image").on("click", function(){$('#album_settings_album_cover').val('default');});
						album_settings_check_flag_private();
						$("#album_settings_private").on("click", function(){album_settings_check_flag_private();});
        }
    });
}

function album_settings_check_flag_private(){
	if ($('#album_settings_private').prop('checked') == true){
		$("#album_settings_only_private").prop("disabled", false);
	}else{
		$('#album_settings_only_private').prop("checked", false);
		$("#album_settings_only_private").attr("disabled", true);
	}
}

function AddNewAlbum(){
	var buttons = {};
	buttons[language['lang']['new_album_button']['save']] = function () {write_NewAlbum();}
	buttons[language['lang']['new_album_button']['cancel']] = function () {$(this).dialog("close");}
	html = "<div><table>";
	html += "<tr><td>"+language['lang']['new_album']['album_name']+":</td><td><input type='text' class='new_album_class' id='new_album_name' placeholder='Album Name'/></td></tr>";
	html += "<tr><td>"+language['lang']['new_album']['path']+":</td><td><input type='file' class='new_album_class' id='new_album_path' webkitdirectory directory multiple/></td></tr>";
	html += "<tr><td>"+language['lang']['new_album']['cover']+":</td><td><input type='text' class='new_album_class' id='new_album_cover_image' value='default'/></td></tr>";
	html += "<tr><td>"+language['lang']['new_album']['start_date']+":</td><td><input type='text' class='new_album_class' id='new_album_start_date' value='0000-00-00'/>";
	html += 	"<div id='clear_new_album_start_date' class='clear_field img_small display_img_inline' title='"+language['lang']['new_album_title']['start_date']+"'></div></td></tr>";
	html += "<tr><td>"+language['lang']['new_album']['end_date']+":</td><td><input type='text' class='new_album_class' id='new_album_end_date' value='0000-00-00'/>";
	html += 	"<div id='clear_new_album_end_date' class='clear_field img_small display_img_inline' title='"+language['lang']['new_album_title']['end_date']+"'></div></td></tr>";
	html += "<tr><td>"+language['lang']['new_album']['show_private']+":</td><td><input type='checkbox' class='new_album_class' id='new_album_album_private'/></td></tr>";
	html += "</table></div>";
	CreateDialog(language['lang']['new_album']['dialog_title'], html, "30%", buttons);
	$("#new_album_start_date").datepicker({dateFormat:"yy-mm-dd"});
	$("#new_album_end_date").datepicker({dateFormat:"yy-mm-dd"});
	$("#clear_new_album_start_date").on("click", function(){$('#new_album_start_date').val('0000-00-00');});
	$("#clear_new_album_end_date").on("click", function(){$('#new_album_end_date').val('0000-00-00');});

	//get folder name from FILE input webkit and write it in "album name" text input
	document.getElementById("new_album_path").addEventListener("change", function(event) {
	  let files = event.target.files;
		$('#new_album_name').val(files[0].webkitRelativePath.replace('/'+files[0].name,''));
	},
	false);
}

function edit_general_settings(){
	var language = $('#select_language').val();
	var theme = $('#select_theme').val();
	var admin_private = (($('#admin_private').prop('checked') == true) ? 1 : 0);
	var admin_show_album_settings = (($('#admin_show_album_settings').prop('checked') == true) ? 1 : 0);
	var admin_show_picture_settings = (($('#admin_show_picture_settings').prop('checked') == true) ? 1 : 0);
	var admin_show_big_title = (($('#admin_show_big_title').prop('checked') == true) ? 1 : 0);
	$.ajax({
				url: './script/edit_general_settings.php',
				method: 'POST',
				data: {language:language, admin_private: admin_private, admin_show_album_settings:admin_show_album_settings, admin_show_picture_settings:admin_show_picture_settings, theme:theme, admin_show_big_title:admin_show_big_title},
				dataType: 'json',
				success: function(res) {
						if (res['status'] == "OK"){
							$('#dialog').dialog('close');
							location.reload();
						}else{
							console.log(res);
						}
				}
		});
}

function edit_picture_settings(picture_id){
	var private = (($('#picture_settings_private').prop('checked') == true) ? 1 : 0);
	var visible = (($('#picture_settings_visible').prop('checked') == true) ? 1 : 0);
	var cover_image = (($('#picture_settings_cover_image').prop('checked') == true) ? 1 : 0);
	var favorite = (($('#picture_settings_favorites').prop('checked') == true) ? 1 : 0);

	$.ajax({
				url: './script/edit_picture_settings.php',
				method: 'POST',
				data: {picture_id:picture_id, private: private, visible:visible, cover_image:cover_image, favorite:favorite},
				dataType: 'json',
				success: function(res) {
						if (res['status'] == "OK"){
							$('#dialog').dialog('close');
							location.reload();
						}else{
							console.log(res);
						}
				}
		});
}

function edit_album_settings(album_id){
	var name = $('#album_settings_album_name').val();
	var cover_image = $('#album_settings_album_cover').val();
	var start_date = $('#album_settings_start_date').val();
	var end_date = $('#album_settings_end_date').val();
	var album_private = (($('#album_settings_private').prop('checked') == true) ? 1 : 0);
	var album_only_private = (($('#album_settings_only_private').prop('checked') == true) ? 1 : 0);
	$.ajax({
				url: './script/edit_album_settings.php',
				method: 'POST',
				data: {album_id: album_id, name:name, cover_image:cover_image, start_date:start_date, end_date:end_date, album_private:album_private, album_only_private:album_only_private},
				dataType: 'json',
				success: function(res) {
						if (res['status'] == "OK"){
							$('#dialog').dialog('close');
							location.reload();
						}
				}
		});
}

function write_NewAlbum(){
	new_album_data = {};
	$('.new_album_class').each(function(){
		if ($(this).attr('type') == 'checkbox'){
			if ($(this).prop('checked') == true){
				new_album_data[this.id] = true;
			}else{
				new_album_data[this.id] = false;
			}
		}else if($(this).attr('type') == 'file'){
			var list_of_files = this.files.length;
			if (this.files.length > 0){
				new_album_data[this.id] = $("#new_album_path")[0].files[0].webkitRelativePath;
			}else{
				new_album_data[this.id] = '';
			}
		}else{
			new_album_data[this.id] = this.value;
		}
	});
	if (new_album_data["new_album_name"] == ''){alert(language['lang']['new_album_alert']['album_name']);$("#new_album_name").focus();return;}
	if (new_album_data["new_album_path"] == ''){alert(language['lang']['new_album_alert']['album_path']);return;}
	$.ajax({
				url: './script/write_NewAlbum.php',
				method: 'POST',
				data: {new_album_data: new_album_data,album_root_folder:glob_general_settings['album_root_folder']['album_root_folder']},
				dataType: 'json',
				success: function(res) {
					if (res['status'] == "OK"){
						$('#dialog').dialog('close');
						location.reload();
						console.log(general_settings);
					}
				}
		});
}

function deleteAlbum(album_id){
	if (confirm(language['lang']['albums_settings_confirm']['delete'])){
		$.ajax({
					url: './script/delete_album.php',
					method: 'POST',
					data: {album_id: album_id},
					dataType: 'json',
					success: function(res) {
						if (res['status'] == "OK"){
							$('#dialog').dialog('close');
							location.reload();
						}
					}
			});
	}
}

function removeFavorite(id_picture){
	$.ajax({
				url: './script/remove_favorite.php',
				method: 'POST',
				data: {id_picture: id_picture},
				dataType: 'json',
				success: function(res) {
					if (res['status'] == "OK"){
						location.reload();
					}
				}
		});
}

function select_panel(panel_to_show){
	for (panel_to_hide in tmpPanes){
		$('#'+tmpPanes[panel_to_hide]).hide();
		$('#btn_'+tmpPanes[panel_to_hide]).removeClass('selected_pane');
	}
		$('#'+panel_to_show).show();
		$('#btn_'+panel_to_show).addClass('selected_pane');
}

function CreateDialog(title, html, p_width, buttons, panes, res){
	EmptyDialog('dialog');
	tmpPanes = {};
	$( "#dialog" ).html(html);
	for (panel in panes){
			$('#settings_panes').append("<div id='btn_"+panes[panel]+"' class='panel_button' onClick='select_panel(\""+panes[panel]+"\")' >" + panel + "</div>");
	}
	$(".panel_button").first().addClass('selected_pane');
	tmpPanes = panes;
	$( "#dialog" ).dialog({
		resizable: false,
		height: "auto",
		title: title,
		width: p_width,
		modal: true,
		buttons: buttons
	});
}

function start_loading(){
	$('#loading').show();
}

function stop_loading(){

}

function EmptyDialog(dialog){
	$('#'+ dialog).empty();
}

function getLanguage(){
	$.ajax({
				url: './script/get_language.php',
				method: 'POST',
				dataType: 'json',
				success: function(res) {
					language['lang'] = res[0];
				}
		});
}

function getConfig(){
	$.ajax({
				url: './script/getGeneralSettings.php',
				method: 'POST',
				dataType: 'json',
				data: {response: "response"},
				success: function(res) {
					glob_general_settings = res;
				}
		});
}

$( document ).ready(function() {
	getLanguage();
	getConfig();
	//start_loading();
});
