/**

 * Class FCKeditor

 * Ext & FCKeditor Integration

 *

 * @author <a href="mailto:hb562100@163.com">hb562100</a>

 * @version 2008-12-11

 *

 * @extend Ext.Component

 */





/**

 * @Support ext's layout.

 *        You can use ext's layout to fit the container of editor(as 'fit') ,instead of set 'Height' and 'Width'.

 *

 * @Support change toolbarset dynamically.

 *        Use <b>setToolbarSet</b> to do this. Code maybe like : <i>Ext.getCmp(editor.Name).setToolbarSet('Basic');</i>

 *

 * @Support events : resize , toolSetChanged , editorRender and Ext.Component's events.

 *

 * <ul>

 * <li>Property id doesn't work now , it will be set the same as 'Name',so you wanna to get this component ,use Ext.getCmp(Name); </li>

 * <li>Property Name , MUST be set.</li>

 * <li>The FCKeditor's direct Properties :  Width,Height,ToolbarSet,Value,BasePath,CheckBrowser</li>

 * <li>The FCKeditor's Config Properties( the same as fckConfig ): refer to <a href="http://docs.fckeditor.net/FCKeditor_2.x/Developers_Guide/Configuration/Configuration_Options">http://docs.fckeditor.net/FCKeditor_2.x/Developers_Guide/Configuration/Configuration_Options</a></li>

 * </ul>

 */



Ext.ux.FCKeditor = Ext.extend(Ext.Component,

    {

        constructor : function( conf ){

            if(!conf.editor){

                conf.editor

                    = new FCKeditor(

                        conf.Name

                        ,conf.Width

                        ,conf.Height

                        ,conf.ToolbarSet

                        ,conf.Value

                    );

            }



            Ext.apply(

                conf  , conf.editor

            );



            Ext.apply(conf.Config,

                conf.fckConfig

            );



            //define/modify ext's component id

            conf.id = conf.Name;



            Ext.ux.FCKeditor.superclass.constructor.call(this, conf);



            this.addEvents(

                [

                     /**

                     * @Event Function(editor)

                     * Fires after the size of the editor is changed.

                     */

                    'resize',



                    /**

                     * @Event Function(editor , toolset)

                     * Fires after the toolset of the editor is changed.

                     */

                    'toolSetChanged',





                    /**

                     * @Event Function(editor)

                     * Fires after the editor is rendered.

                     */

                     'editorRender'



                ]

            );

        },

        //private

        onRender: function(ct, position){

            if(!this.tpl){

                this.tpl = new Ext.XTemplate(

                    '<div class="x-window-mc">',this.CreateHtml(),'</div>'

                );

            }

            if(position){

                this.el = this.tpl.insertBefore(position,this ,true);

            }else{

                this.el = this.tpl.append(ct,this ,true);

            }

        },

        /**

         * Get inner FCKeditor instance.

         */

        getInnerEditor : function(){

            /**

             * Use FCKeditorAPI.GetInstance() maybe cause null erorr,

             * when call 'Create' or 'ReplaceTextarea' twice more.

             */



            /*if(FCKeditorAPI)

                return    FCKeditorAPI.GetInstance(this.Name);*/



            /**

             * Ext.ux.FCKeditorMgr can automatic register FCKeditor when the editor was  rendered.

             */

            return    Ext.ux.FCKeditorMgr.get(this.Name);

        },



        /**

         * This method can fire 'toolSetChanged' event.

         * @param {String} set , the set of toolbar

         */

        setToolbarSet : function(set){

            var inner = this.getInnerEditor();

            if(inner)

                this.Value = inner.GetData();



            this.ToolbarSet = set;

            this.el.dom.innerHTML=this.CreateHtml();



            this.fireEvent( 'toolSetChanged' , this, set);

        },



        /**

         * Set this size of editor.

         *

         * @param {Object} size ,{ width : ...,  height: ...}

         * @param {Boolean/Object} , use animate

         */

        setSize : function(size ,animate){

            if(!this. rendered)

                return ;

            var domEl = this.getEditorFrame();



            if(domEl)

                domEl.setSize(size.width ,size.height,animate);



            //var size =  this.getSize();



            this.Width = size.width;

            this.Height = size.height;



            this.fireEvent( 'resize' , this);

        },

        /**

         * Get this size of editor.

         *

         * return {Object} {width: {Number} , height:{Nnmber}}

         */

        getSize : function(){

            return this.el.getSize();

        },

        /**

         * Set this height of editor.

         * @param {String} h ,height

         */

        setHeight : function(h){

            if(!this. rendered)

                return ;



            var domEl = this.getEditorFrame();



            if(domEl)

                domEl.setHeight(h);



            this.Height = this.getHeight();



            this.fireEvent( 'resize' , this);

        },

        /**

         * Get this height of editor.

         * @return {Nunber}

         */

        getHeight : function(){

            return  this.el.getHeight();

        },

        /**

         * Set this width  of editor.

         * @param {String} w ,width

         */

        setWidth : function(w){

            if(!this. rendered)

                return ;



            var domEl = this.getEditorFrame();



            if(domEl)

                domEl.setWidth(w);



            this.Width = this.el.getWidth();



            this.fireEvent( 'resize' , this);

        },

        /**

         * Get this width of editor.

         * @return {Nunber}

         */

        getWidth : function(){

            return  this.el.getWidth();

        },



        /**

         * The value of editor's Getter & Setter.

         */

        setValue : function(html){

            this.getInnerEditor().SetData(html);

        },

        getValue : function(){

            return this.getInnerEditor().GetData();

        },



        //private

        getEditorFrame : function(){

            if(!this. rendered)

                return null;



            var dom =

                Ext.get(

                    Ext.query("iframe",

                        this.el.dom

                    )[0]

                );

            return Ext.get(dom);

        },

        /**

         * override

         */

        destroy: function(){

            Ext.ux.FCKeditorMgr.remove(this.Name);

            Ext.ux.FCKeditor.superclass.destroy.call(this);

        }

    }

);



Ext.ux.FCKeditorMgr = (function(){

    var collections = new Object();

    return {

            register : function (name , o){

                collections[name] = o;

                var editor = Ext.getCmp(name);



                if(editor.ownerCt){

                    editor.ownerCt.doLayout();

                }

                editor.fireEvent('editorRender',editor);

            },

            remove : function(name){

                delete collections[name];

            },

            get :function (name){

                return collections[name];

            }

        };

})();



FCKeditor_OnComplete =

typeof FCKeditor_OnComplete == 'function'?

    FCKeditor_OnComplete.createSequence(

        function( instance ){

            Ext.ux.FCKeditorMgr.register(instance.Name , instance);

        },FCKeditor_OnComplete):

    function( instance ){

            Ext.ux.FCKeditorMgr.register(instance.Name , instance);

        };



Ext.reg('fckeditor' , Ext.ux.FCKeditor);
