/*!
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

(function($) {

var options = {
	onFormLoad: function(modal) {
		FieldManager.fireEvent('fieldModalDisplay', modal)

		EE.cp.fieldToggleDisable(modal)

		// Only bind ee_url_title for new fields
		if ($('input[name=field_name]').val() == '') {
			$('input[name=field_label]', modal).bind("keyup keydown", function() {
				$(this).ee_url_title('input[name=field_name]', true);
			})
		}
	}
}

var fieldsForm = new MutableSelectField('category_fields', Object.assign(EE.categoryField, options))

})(jQuery);
