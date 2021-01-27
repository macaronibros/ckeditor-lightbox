/**
 * DO NOT EDIT THIS FILE.
 * See the following change record for more information,
 * https://www.drupal.org/node/2815083
 * @preserve
 **/

(function (CKEDITOR) {
  function findElementByName(element, name) {
    if (element.name === name) {
      return element;
    }

    var found = null;
    element.forEach(function (el) {
      if (el.name === name) {
        found = el;

        return false;
      }
    }, CKEDITOR.NODE_ELEMENT);
    return found;
  }

  CKEDITOR.plugins.add('drupallightbox', {
    requires: 'drupalimage',

    beforeInit: function beforeInit(editor) {

      editor.on('widgetDefinition', function (event) {

        var widgetDefinition = event.data;
        if (widgetDefinition.name !== 'image') {
          return;
        }

        var originalDowncast = widgetDefinition.downcast;
        widgetDefinition.downcast = function (element) {
          var img = findElementByName(element, 'img');
          originalDowncast.call(this, img);

          var attrs = img.attributes;

          attrs['data-lightbox'] = this.data.lightbox;

          return img;
        };

        var originalUpcast = widgetDefinition.upcast;
        widgetDefinition.upcast = function (element, data) {
          if (element.name !== 'img') {
            return;
          }

          var lightbox = element.attributes['data-lightbox'];

          element = originalUpcast.call(this, element, data);

          data['lightbox'] = lightbox;

          return element;

        };


        CKEDITOR.tools.extend(widgetDefinition._mapDataToDialog, {
          'lightbox': 'data-lightbox'
        });

        var originalCreateDialogSaveCallback = widgetDefinition._createDialogSaveCallback;
        widgetDefinition._createDialogSaveCallback = function (editor, widget) {
          var saveCallback = originalCreateDialogSaveCallback.call(this, editor, widget);

          return function (dialogReturnValues) {
            saveCallback(dialogReturnValues);
          };
        }

      }, null, null, 20);
    },

  });
})(CKEDITOR);