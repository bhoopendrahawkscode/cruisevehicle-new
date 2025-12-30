
const counts = [

  { dropdown_menu_id: "#dropdown_menu_id_ul li", dropdown_toggle_id: "#dropdown_menu_id_manage" },
  { dropdown_menu_id: "#notification_ul li", dropdown_toggle_id: "#notification_manage" },
  { dropdown_menu_id: "#report_ul li", dropdown_toggle_id: "#report_manage" },
  { dropdown_menu_id: "#dropdown_ul li", dropdown_toggle_id: "#dropdown_manage" },
  { dropdown_menu_id: "#page_ul li", dropdown_toggle_id: "#page_menu" },
  { dropdown_menu_id: "#media_menu_ul li", dropdown_toggle_id: "#media_menu" },
  { dropdown_menu_id: "#user_management_ul li", dropdown_toggle_id: "#user_manage" },
  { dropdown_menu_id: "#cms_management_ul li", dropdown_toggle_id: "#cms_management" },
  { dropdown_menu_id: "#post_management_ul li", dropdown_toggle_id: "#post_management" },




];

counts.forEach(({ dropdown_menu_id, dropdown_toggle_id }) => {
  const count = $(dropdown_menu_id).length;
  if (count > 0) {
    $(dropdown_toggle_id).css("display", "block");
  } else {
    $(dropdown_toggle_id).css("display", "none");
  }

});

// $('.dropdown-toggle').dropdown();
