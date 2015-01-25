#     $('.target').addImages({ paramA: 'not-foo' });
#     $('.target').addImages('myMethod', 'Hello, world');
(($, window) ->
  class AddImages

    $container: null
    prototype:  null
    defaults:
      container: null
      dataField: 'prototype'

    constructor: (el, options) ->
      @options = $.extend({}, @defaults, options)
      @$el = $(el)

      @$container = $(@options.container)
      @prototype = @$el.data(options.dataField)

      $('a.car_image_add').on "click", (e) =>
        @addNewImage()

      $("a.car_image_remove").on "click", (ev) =>
        ev.preventDefault()
        $(ev.target).parents("li").remove()

    addNewImage: () ->
      index = @$container.find(':input').length
      $proto = $(_.unescape(@prototype.replace /__name__/g, index))
      $new = $("<li />").append $proto

      @$container.append $new

  $.fn.extend addImages: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('addImages')

      if !data
        $this.data 'addImages', (data = new AddImages(this, option))
      if typeof option == 'string'
        data[option].apply(data, args)

) window.jQuery, window
