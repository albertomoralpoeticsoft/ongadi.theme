const $image = data => {

  return `
  <div class="Image">
    <div class="Thumb">
      <img src="https://ongadi.org/wp-content/uploads/2024/06/la-cocinera-50x70-1.jpg">
    </div>
    <div class="Title">
      <span>
        <span class="">la coc</span>
        <mark class="Highlight ">in</mark>
        <span class="">era 50x70</span>
      </span>
    </div>
    <div class="Legend">
      <span></span>
    </div>
    <div class="Description">
      <span></span>
    </div>
  </div>
  `
}

export default $ => {

  const $imagebank = $('.wp-block-ongadi-imagebank')
  const $blockattributes = $imagebank.find('#blockattributes')
  const $search = $imagebank.find('.Search')
  const $searchinput = $search.find('input')
  const $searchbutton = $search.find('.wp-block-button a')
  const $results = $imagebank.find('.Results')
  const $resultsmessage = $results.find('.Message')
  const $resultslist = $results.find('.List')
  const $listmessage = $resultslist.find('.ListMessage')
  const $listposts = $resultslist.find('.ListPosts')

  const attributesstr = $blockattributes.text()
  const cleanjson = attributesstr
  .split('“').join('"')
  .split('”').join('"')
  const blockattributes = JSON.parse(cleanjson)
  const columns = blockattributes.columns
  let folders = []
  if(blockattributes.folders) {

    folders = JSON.parse(blockattributes.folders)
  }

  $searchinput
  .on(
    'keyup',
    function() {

      const $this = $(this)
      const text = $this.val()
      if(text.length > 3) {

        $searchbutton.removeClass('disabled')

      } else {

        $searchbutton.addClass('disabled')

      }
    }
  )

  $searchbutton
  .on(
    'click',
    function() {

      const $this = $(this)
      if(!$this.hasClass('disabled')) {

        console.log('active')
      }
    }
  )
}