#     $('.target').addImages({ paramA: 'not-foo' });
#     $('.target').addImages('myMethod', 'Hello, world');
(($, window) ->
  class collection

    $container: null
    $form: null
    prototype:  null

    defaults:
      container: null
      dataField: 'prototype'
      regex: /__name__/g

    constructor: (el, options) ->
      @options = $.extend({}, @defaults, options)
      @$container = $(el)
      @$form = $(@options.formSelector)
      @prototype = @$form.data options.dataField

      $("a.item_add", @$container.parent()).on "click.collection", (event) =>
        event.preventDefault()
        @addNewItem()

      @$container.on "click.collection", "a.item_remove", (event) =>
        event.preventDefault()
        $(event.target).parents("li").remove()

    addNewItem: () =>
      index = @$container.find("ul.collection-container").children().length
      $proto = $(_.unescape(@prototype.replace @options.regex, index))
      $("ul.collection-container", @$container).append $proto

  $.fn.extend collection: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('collection')

      if !data
        $this.data 'collection', (data = new collection(this, option))
      if typeof option == 'string'
        data[option].apply(data, args)

) window.jQuery, window
