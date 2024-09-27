// https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block/
// https://rudrastyh.com/gutenberg/query-loop-block-variation.html
// https://wpfieldwork.com/modify-query-loop-block-to-filter-by-custom-field/

const { registerBlockVariation } = wp.blocks

const ONGADI_IMAGEBANK = 'ongadi/imagebank';

registerBlockVariation(
  'core/query', 
  {
    name: ONGADI_IMAGEBANK,
    title: 'Image Bank',
    description: 'Displays search of images in File Bird Folders',
    isActive: ({ 
      namespace, 
      query
    }) => {

      return (
        namespace === ONGADI_IMAGEBANK
        && 
        query.postType === 'attachment'
      );
    },
    icon: 'share',
    attributes: {
      namespace: ONGADI_IMAGEBANK,
      query: {
        perPage: 10,
        pages: 6,
        offset: 0,
        postType: 'attachment',
        order: 'desc',
        orderBy: 'date',
        author: '',
        search: '',
        exclude: [],
        sticky: '',
        inherit: false,
      },
    },
    scope: ['inserter'],
    innerBlocks: [
      [
        'core/post-template',
        {},
        [ 
          ['core/post-title'], 
          ['core/post-excerpt']
        ],
      ],
      [ 'core/query-pagination' ],
      [ 'core/query-no-results' ],
    ],
    allowedControls: ['order'],
  }
);