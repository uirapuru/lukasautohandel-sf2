(($, window) ->
  class searchForm
    $form: null
    brandSelector: '#car_filters_brand'
    modelSelector: '#car_filters_model'

    constructor: (form, options) ->
      @$form = $(form)

      @$form.on 'change.brandChange', @brandSelector, (e) =>
        @handleBrandSwitch(e)

    setModelsForBrand: (brandId) =>
      console.log brandId

      if brandId == ''
        url = Routing.generate 'api_models'
      else
        url = Routing.generate 'models_for_brand', {id : brandId}

      $.getJSON url, {}, @fillModelsList

    fillModelsList: (data) =>
      $modelSelect = $(@modelSelector)
      $modelSelect.empty()
      $.each data, (i, element) ->
        $option = $("<option />").text(element.name).val(element.id)
        $modelSelect.append $option

    handleBrandSwitch: (e) =>
      $item = $(e.target)
      brandId = $item.val()
      @setModelsForBrand brandId

  $.fn.extend searchForm: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('searchForm')

      if !data
        $this.data 'searchForm', (data = new searchForm(this, option))
      if typeof option == 'string'
        data[option].apply(data, args)

) window.jQuery, window
