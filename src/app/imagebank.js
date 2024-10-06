const gettext = (text, data) => {
  
  switch(text) {

    case 'writesearch': return `Escribe un texto para buscar`

    case 'resultstats': return `
      Encontrado 
      <strong>${ data.text }</strong> 
      en 
      <strong>${ data.found }</strong> 
      de 
      <strong>${ data.total }</strong> 
      imágenes
    `
    case 'searching': return `
      Buscando 
      <strong> ${ data.terms }</strong>
      ...
    `
    default:

      break
  }
}

const image = data => {

  return `
  <div 
    class="Image"
    data-guid="${ data.guid }"
  >
    <div class="Thumb">
      <img src="${ data.thumb }">
    </div>
    <div class="Title">
      ${ data.post_title }
    </div>
    <div class="Dimensions">
      <span class="Width">
        ${ data.width }
      </span>
      <span class="X">
      x
      </span>
      <span class="Height">
        ${ data.height }
      </span>
      <span class="PX">
      px
      </span>
    </div>
    <div class="Size">
      ${ data.size }
    </div>
  </div>
  `
}

export default $ => {

  // DOM

  const $imagebank = $('.wp-block-ongadi-imagebank')
  const $blockattributes = $imagebank.find('#blockattributes')
  const $search = $imagebank.find('.Search')
  const $searchinput = $search.find('input')
  const $searchbutton = $search.find('.wp-block-button')
  const $searchbuttonlink = $searchbutton.find('a')
  const $results = $imagebank.find('.Results')
  const $resultsmessage = $results.find('.Message')
  const $resultslist = $results.find('.List')
  const $listmessage = $resultslist.find('.ListMessage')
  const $listposts = $resultslist.find('.ListPosts')

  // Block data

  const attributesstr = $blockattributes.text()
  const cleanjson = attributesstr
  .split('“').join('"')
  .split('”').join('"')
  const blockattributes = JSON.parse(cleanjson)
  console.log()
  const columns = blockattributes.columns
  let folders = []
  if(blockattributes.folders) {

    folders = JSON.parse(blockattributes.folders).join(',')
  }

  // State

  const setstate = (action, data) => {

    switch(action) {

      case 'writing':

        if(data.text.length > 3) {

          $searchbutton.removeClass('disabled')
          $resultsmessage.html('')

        } else {

          $searchbutton.addClass('disabled')
        }

        break

      case 'searching':

        $resultsmessage.html(
          gettext(
            'searching',
            {
              terms: $searchinput.val()
            }
          )
        )   
        
        $listmessage.html('')
        $listposts.html('')

        fetch(
          '/wp-json/ongadi/imagebank/search',
          {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              text: $searchinput.val(),
              folders: folders
            })
          }
        )
        .then(response => response.json().then(result => {

          setstate(
            'results',
            result
          )

        }))

        break

      case 'results':

        $resultsmessage.html('') 
        
        $listmessage.html(gettext(
          'resultstats',
          {
            text: data.terms.join(' '),
            found: data.postcount,
            total: data.attachmentcount
          }
        ))

        $listposts.attr('class', 'ListPosts Columns_' + columns)
        $listposts.html(
          data.posts
          .map(
            post => image(post)
          )
        )

        $listposts.find('.Image')
        .on(
          'click',
          function() {

            const guid = $(this).data('guid')
            
            setstate(
            'viewimage',
            guid
          )

          }
        )

        break

      case 'viewimage':

        $('body').append(`
          <div id="ImageViewer">
            <img src="${ data }" />
            <div class="wp-block-button disabled">
              <a 
                class="wp-block-button__link wp-element-button " 
                href="#"
              >
                x
              </a>
            </div>
          </div>
        `)

        $('html').css('overflow', 'hidden')
        $('html').scrollTop(0)  

        $('#ImageViewer .wp-block-button')
        .on(
          'click',
          function() {

            $('#ImageViewer').remove()
            $('html').css('overflow', 'auto') 
          }
        )

        break

      default:

        break
    }
  }

  $searchinput
  .on(
    'keyup',
    function() {

      setstate(
        'writing',
        {
          text: $(this).val()
        }
      )
    }
  )

  $searchbuttonlink
  .on(
    'click',
    function() {

      if(!$searchbutton.hasClass('disabled')) {

        setstate('searching')
      }
    }
  )

  // Init 

  if($searchinput.val()) { // Debug text

    setstate(
      'writing',
      {
        text: $searchinput.val()
      }
    )
    
  } else {
    
    $resultsmessage.html(gettext('writesearch'))
  }
}