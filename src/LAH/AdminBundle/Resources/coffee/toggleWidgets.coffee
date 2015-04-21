(($, window) ->
  class toggleWidgets
    @$normal: null
    @$toggled: null
    @$button: null
    @$close: null

    constructor: (el, options) ->
      @$button = $(el)
      @$normal = $(options.normal)
      @$toggled = $(options.toggled)
      @$close = $(options.close)

      @$toggled.hide()

      @$button.on "click", (e) =>
        if @$normal.is ":visible"
          @$normal.hide()
          @$toggled.show()
        else
          @$normal.show()
          @$toggled.hide()

      @$close.on "click", (e) =>
        @$toggled.find("input").val ""
        @$button.trigger "click"

  $.fn.extend toggleWidgets: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('toggleWidgets')

      if !data
        $this.data 'toggleWidgets', (data = new toggleWidgets(this, option))
      if typeof option == 'string'
        data[option].apply(data, args)

) window.jQuery, window
