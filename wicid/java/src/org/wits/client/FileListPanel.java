/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.wits.client;


import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.data.BaseListLoader;
import com.extjs.gxt.ui.client.data.HttpProxy;
import com.extjs.gxt.ui.client.data.JsonLoadResultReader;
import com.extjs.gxt.ui.client.data.ListLoadResult;
import com.extjs.gxt.ui.client.data.ModelData;
import com.extjs.gxt.ui.client.data.ModelType;
import com.extjs.gxt.ui.client.event.Events;
import com.extjs.gxt.ui.client.event.Listener;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.event.SelectionChangedEvent;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.BaseEvent;
import com.extjs.gxt.ui.client.event.GridEvent;
import com.extjs.gxt.ui.client.store.ListStore;
import com.extjs.gxt.ui.client.widget.ContentPanel;
import com.extjs.gxt.ui.client.widget.LayoutContainer;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.button.ButtonBar;
import com.extjs.gxt.ui.client.widget.form.CheckBox;
import com.extjs.gxt.ui.client.widget.grid.CellEditor;
import com.extjs.gxt.ui.client.widget.grid.CheckBoxSelectionModel;
import com.extjs.gxt.ui.client.widget.grid.ColumnConfig;
import com.extjs.gxt.ui.client.widget.grid.ColumnModel;
import com.extjs.gxt.ui.client.widget.grid.EditorGrid;
import com.extjs.gxt.ui.client.widget.layout.FitLayout;
import com.extjs.gxt.ui.client.widget.menu.Menu;
import com.extjs.gxt.ui.client.widget.toolbar.ToolBar;
import com.extjs.gxt.ui.client.widget.MessageBox;

import com.google.gwt.user.client.Window;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.user.client.Element;

import java.util.List;
import java.util.ArrayList;
/**
 *
 * @author nguni
 */
public class FileListPanel extends LayoutContainer {
    private BaseListLoader<ListLoadResult<ModelData>> loader;
    private EditorGrid<ModelData> grid;
    private ColumnModel cm;
    private CheckBoxSelectionModel<ModelData> sm;
    private ListStore<ModelData> store;
    private Main main;
    private String defaultParams = "";
    private String currentPath;
    private Button refreshButton = new Button("Refresh");
    private ToolBar toolbar = new ButtonBar();
    

    public FileListPanel(Main main) {
        super();
        this.main = main;
    }

    @Override
    protected void onRender(Element parent, int index) {
        super.onRender(parent, index);
        List<ColumnConfig> columns = setColumns();
        sm = new CheckBoxSelectionModel<ModelData>();
        // create the column model
        cm = new ColumnModel(columns);
        //editButton.setEnabled(false);
        // defines the xml structure
        ModelType type = setTypes();
        setCurrentPath();
        defaultParams = "?module=wicid&action=getfiles&node=" + currentPath;
        // use a http proxy to get the data

        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, GWT.getHostPageBaseURL() + defaultParams);
        HttpProxy<String> proxy = new HttpProxy<String>(builder);
        // need a loader, proxy, and reader
        JsonLoadResultReader<ListLoadResult<ModelData>> reader = new JsonLoadResultReader<ListLoadResult<ModelData>>(type);
        loader = new BaseListLoader<ListLoadResult<ModelData>>(proxy,
                reader);
        store = new ListStore<ModelData>(loader);
        grid = createGrid();

        ContentPanel panel = new ContentPanel();
        panel.setFrame(true);
        panel.setButtonAlign(HorizontalAlignment.CENTER);
        
        refreshButton.setIconStyle("refresh");
        refreshButton.addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                //MessageBox.info("hello", "hello world", null);
                setCurrentPath();
                defaultParams = "?module=wicid&action=getfiles&node=" + currentPath;
                refreshFileList(defaultParams);
            }
        });

        toolbar.add(refreshButton);
        panel.setTopComponent(toolbar);
        panel.setFrame(false);
        panel.setBodyBorder(false);
        panel.setLayout(new FitLayout());
        panel.add(grid);

        panel.setWidth("100%");
        panel.setHeight(Window.getClientHeight());

        add(panel);
        refreshFileList(defaultParams);
    }

    public List<ColumnConfig> setColumns() {
        List<ColumnConfig> columns = new ArrayList<ColumnConfig>();
        sm = new CheckBoxSelectionModel<ModelData>();
        //columns.add(sm.getColumn());
        columns.add(new ColumnConfig("FileName", "File Name", 100));
        columns.add(new ColumnConfig("RefNo", "Ref No", 100));
        columns.add(new ColumnConfig("Owner", "Owner", 145));
        columns.add(new ColumnConfig("LastModified", "Last Modified", 150));
        columns.add(new ColumnConfig("filesize", "File Size", 80));
        columns.add(new ColumnConfig("thumbnailpath", "Icon", 100));

        return columns;

    }

    public ModelType setTypes() {
        ModelType type = new ModelType();
        type.setRoot("files");
        type.addField("FileName", "text");
        type.addField("RefNo", "refno");
        type.addField("Owner", "owner");
        type.addField("LastModified", "lastmod");
        type.addField("filesize", "filesize");
        type.addField("thumbnailpath", "thumbnailpath");

        return type;
    }

    public EditorGrid<ModelData> createGrid() {
        grid = new EditorGrid<ModelData>(store, cm);
        grid.setBorders(true);
        grid.setLoadMask(true);
        grid.getView().setEmptyText("No files found.");
        grid.setAutoExpandColumn("FileName");
        grid.addPlugin(sm);
        grid.setSelectionModel(sm);
        grid.addListener(Events.CellDoubleClick, new Listener<GridEvent>(){
            public void handleEvent(GridEvent ge) {
                if(ge.getColIndex() >= 0) {
                    int gridCurrentRow = ge.getRowIndex(), gridCurrentCol = ge.getColIndex();
                    String RefNo = grid.getView().getCell(gridCurrentRow, 1).getInnerText();
                    // get info in database for each row selected. RefNo is unique

                }
            }
        });

        return grid;
    }
     public void refreshFileList(String params) {
        // defines the xml structure
        ModelType type = setTypes();
        
        // use a http proxy to get the data
        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + params);
        HttpProxy<String> proxy = new HttpProxy<String>(builder);

        // need a loader, proxy, and reader
        JsonLoadResultReader<ListLoadResult<ModelData>> reader = new JsonLoadResultReader<ListLoadResult<ModelData>>(type);

        loader = new BaseListLoader<ListLoadResult<ModelData>>(proxy,
                reader);

        store = new ListStore<ModelData>(loader);

        grid.reconfigure(store, cm);
        loader.load();
    }

     public void setCurrentPath() {
         currentPath = main.getCurrentPath();
     }

}
