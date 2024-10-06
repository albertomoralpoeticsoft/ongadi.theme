import immutableUpdate from 'immutable-update'
const { __ } = wp.i18n
const { registerBlockType } = wp.blocks
const { 
  InspectorControls
} = wp.blockEditor
const {
	PanelBody,
	PanelRow,
  SelectControl,
  RangeControl,
  TextControl,
  Button
} = wp.components
const {
  useEffect,
  useReducer
} = wp.element

const Image = props => {

  return <div className="Image">
    <div className="Thumb">
      <img src={ props.thumb } />
    </div>
    <div className="Title">
      { props.post_title }
    </div>
    <div className="Dimensions">
      <span className="Width">
        { props.width }
      </span>
      <span className="X">
      x
      </span>
      <span className="Height">
        { props.height }
      </span>
      <span className="PX">
      px
      </span>
    </div>
    <div className="Size">
      { props.size }
    </div>
  </div>
}

const edit = props => {

  const [state, dispatch] = useReducer(
    (state, action) => { 

      const newstate = immutableUpdate(
        state,
        action
      )      
      
      return newstate
    }, 
    {
      tree: [],
      searchtext: '',
      filebirdfolders: '',
      searching: false,
      results: {}
    }
  )

  const settree = (children, list, level) => {

    if(!list) { list = [] }
    if(!level) { level = 0 }

    const leveltabs = [...Array(level).keys()].map(i => ' - ').join('');

    children
    .forEach(
      folder => {

        list.push({
          value: folder.id,
          label: leveltabs + folder.title
        })

        if(folder.children) {

          settree(folder.children, list, level + 1)
        }
      }
    )

    dispatch({
      tree: list
    })
  }

  const selectfolders = values => {

    dispatch({
      results: []
    })

    props.setAttributes({
      folders: JSON.stringify(values)
    }) 
  }

  const setSearchInput = text => {

    dispatch({
      searchtext: text,
      results: []
    })
  }

  const search = () => {

    dispatch({
      searching: true
    }) 

    fetch(
      '/wp-json/ongadi/imagebank/search',
      {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          text: state.searchtext,
          folders: state.filebirdfolders
        })
      }
    )
    .then(response => response.json().then(result => {

      dispatch({
        searching: false,
        results: result
      })
    }))
  }

  useEffect(() => {

    dispatch({
      results: [],
      filebirdfolders: JSON.parse(props.attributes.folders).join(',')
    })

  }, [props.attributes.folders])

  useEffect(() => {

    if(!props.attributes.columns) {

      props.setAttributes({
        columns: 3
      })
    }

    fetch('/wp-json/ongadi/imagebank/folders')
    .then(response => response.json().then(settree))

  }, [])

  return <div className={`
    ${ props.className }
    Edit
  `}>
    <div className="Editor"> 
      <div className="Search">
        <TextControl
          placeholder={ __('Search images') }
          value={ state.searchtext }
          onChange={ setSearchInput }
        />
        <Button 
          variant="secondary"
          disabled={
            !(
              state.searchtext
              &&
              state.searchtext.length > 3
              &&
              state.filebirdfolders
            )
          }
          onClick={ search }
        >
          Buscar
        </Button>
      </div>
      <div className="Results">
        {
          !state.filebirdfolders ?
          <div className="Message">
            Select some File Bird Folder
          </div>
          :
          (
            !state.searchtext
            ||
            state.searchtext.length < 3
          ) ?          
          <div className="Message">
            Write text to search
          </div>
          :
          state.searching ?        
          <div className="Message">
            Searching
          </div>
          :
          !state.results.posts?.length ?          
          <div className="Message">
            No results
          </div>
          :
          <div className="List">
            <div className="ListMessage">
              Found { state.results.postcount } in { state.results.attachmentcount } images
            </div>
            <div className={`
              ListPosts
              Columns_${ props.attributes.columns }
            `}>
              {
                state.results.posts
                .map(image => <Image { ...image } />)
              }
            </div>
          </div>
        }
      </div>    
    </div>
    <InspectorControls>
      <PanelBody 
        title="Settings" 
        initialOpen={ true } 
        className="OngAdi ImageBank InspectorControls"
      >
        <PanelRow className="Controls">
          <div className="FileBirdFolder">
            <SelectControl
              label="Search in FileBird Folders"
              value={ JSON.parse(props.attributes.folders || '[]') }
              multiple
              options={ state.tree }
              onChange={ selectfolders }
            />
          </div>
          <div className="Columns">
            <RangeControl
              label="Layout Columns"
              value={ props.attributes.columns }
              onChange={ 
                value => props.setAttributes({
                  columns: value
                })
              }
              min={ 2 }
              max={ 5 }
            />
          </div>
        </PanelRow>
      </PanelBody>
    </InspectorControls>
  </div>
}

registerBlockType(
  'ongadi/imagebank',
  {
    title: __('Image Bank'),
    icon: 'format-gallery',
    category: 'ongadi',
    attributes: { 
      folders: {
        type: 'string',
        default: '[]'
      },
      columns: {
        type: 'number'
      }
    }, 
    supports: {
      align: true
    },
    edit: edit
  }
)