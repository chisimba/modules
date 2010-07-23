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
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.Events;
import com.extjs.gxt.ui.client.event.Listener;
import com.extjs.gxt.ui.client.event.MenuEvent;
import com.extjs.gxt.ui.client.event.MessageBoxEvent;
import com.extjs.gxt.ui.client.event.SelectionChangedEvent;
import com.extjs.gxt.ui.client.event.SelectionChangedListener;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.store.ListStore;
import com.extjs.gxt.ui.client.widget.ContentPanel;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.LayoutContainer;
import com.extjs.gxt.ui.client.widget.MessageBox;

import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.button.ButtonBar;
import com.extjs.gxt.ui.client.widget.form.CheckBox;
import com.extjs.gxt.ui.client.widget.form.ComboBox;
import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.SimpleComboBox;
import com.extjs.gxt.ui.client.widget.grid.CellEditor;
import com.extjs.gxt.ui.client.widget.grid.CheckBoxSelectionModel;
import com.extjs.gxt.ui.client.widget.grid.ColumnConfig;
import com.extjs.gxt.ui.client.widget.grid.ColumnModel;
import com.extjs.gxt.ui.client.widget.grid.EditorGrid;
import com.extjs.gxt.ui.client.widget.layout.FitLayout;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.extjs.gxt.ui.client.widget.menu.Menu;
import com.extjs.gxt.ui.client.widget.menu.MenuItem;
import com.extjs.gxt.ui.client.widget.menu.SeparatorMenuItem;
import com.extjs.gxt.ui.client.widget.toolbar.ToolBar;
import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.user.client.Element;
import com.google.gwt.user.client.Window;


import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author davidwaf
 */
public class DocumentListPanel extends LayoutContainer {

    private BaseListLoader<ListLoadResult<ModelData>> loader;
    private EditorGrid<ModelData> grid;
    private ColumnModel cm;
    private FormData formData = new FormData("-20");
    private Button editButton = new Button("Edit");
    private Button approveButton = new Button("Approve");
    private Button refreshButton = new Button("Refresh");
    private Button commentButton = new Button("Comment");
    private Button submitButton = new Button("Submit");
    private List<ModelData> selectedRows;
    private CheckBoxSelectionModel<ModelData> sm;
    private boolean removeUsersDone = false;
    private ListStore<ModelData> store;
    private Menu contextMenu = new Menu();
    private Main main;
    private String defaultParams = "", statusS;
    private int status;
    private SimpleComboBox<String> submitCombo = new SimpleComboBox<String>();

    public DocumentListPanel(Main main) {
        super();
        this.main = main;
        editButton.setEnabled(false);
        approveButton.setEnabled(false);
        submitButton.setEnabled(false);
//        checkStatus();
    }

    @Override
    protected void onRender(Element parent, int index) {
        super.onRender(parent, index);

        List<ColumnConfig> columns = new ArrayList<ColumnConfig>();
        sm = new CheckBoxSelectionModel<ModelData>();
        columns.add(sm.getColumn());
        columns.add(new ColumnConfig("Owner", "Owner", 145));
        columns.add(new ColumnConfig("RefNo", "RefNo", 50));
        columns.add(new ColumnConfig("Title", "Title", 150));
        columns.add(new ColumnConfig("Topic", "Topic", 100));
        columns.add(new ColumnConfig("Date", "Date", 70));
        columns.add(new ColumnConfig("Attachment", "Attachment", 70));
        columns.add(new ColumnConfig("Status", "Status", 50));
        columns.add(new ColumnConfig("currentuserid", "Current User", 100));
        CellEditor checkBoxEditor = new CellEditor(new CheckBox());

        // create the column model
        cm = new ColumnModel(columns);
        editButton.setEnabled(false);
        // defines the xml structure
        ModelType type = new ModelType();
        type.setRoot("documents");
        type.addField("userid", "userid");
        type.addField("group", "group");
        type.addField("Owner", "owner");
        type.addField("RefNo", "refno");
        type.addField("Title", "title");
        type.addField("Topic", "topic");
        type.addField("Date", "date");
        type.addField("Attachment", "attachmentstatus");
        type.addField("Status", "status");
        type.addField("currentuserid", "currentuserid");


        // use a http proxy to get the data
        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, GWT.getHostPageBaseURL() + "?module=wicid&action=getdocuments");
        HttpProxy<String> proxy = new HttpProxy<String>(builder);

        // need a loader, proxy, and reader
        JsonLoadResultReader<ListLoadResult<ModelData>> reader = new JsonLoadResultReader<ListLoadResult<ModelData>>(type);
        loader = new BaseListLoader<ListLoadResult<ModelData>>(proxy,
                reader);


        store = new ListStore<ModelData>(loader);

        grid = new EditorGrid<ModelData>(store, cm);
        grid.setBorders(true);
        grid.setLoadMask(true);

        grid.getView().setEmptyText("No documents found.");
        grid.setAutoExpandColumn("Title");
        grid.addPlugin(sm);
        grid.setSelectionModel(sm);
        addContextMenu();
        grid.setContextMenu(contextMenu);
        grid.getSelectionModel().addListener(Events.SelectionChange,
                new Listener<SelectionChangedEvent<ModelData>>() {

                    public void handleEvent(SelectionChangedEvent<ModelData> md) {
                        selectedRows = md.getSelection();
                        approveButton.setEnabled(selectedRows.size() > 0);
                        editButton.setEnabled(selectedRows.size() > 0);
                        submitButton.setEnabled(selectedRows.size() > 0);
                        for (ModelData row : selectedRows) {
                            status = Integer.parseInt(row.get("Status").toString());
                        }
                    }
                });

        for (int i = 0; i < grid.getStore().getCount(); i++) {
            ModelData record = grid.getStore().getAt(i);
            final String statusS = "";
            int statusTemp = Integer.parseInt(record.get("currentuserid").toString());
            System.out.println(statusTemp);
            switch (statusTemp) {
                case 0:
                    statusS.equals("Creation");
                case 1:
                    statusS.equals("APO");
                case 2:
                    statusS.equals("Subfaculty");
                case 3:
                    statusS.equals("Faculty");
                case 4:
                    statusS.equals("Senate");
            }
            record.set("status", statusS);
            grid.getStore().commitChanges();

        }

        ContentPanel panel = new ContentPanel();
        panel.setFrame(true);
        panel.setButtonAlign(HorizontalAlignment.CENTER);
        ToolBar toolbar = new ButtonBar();

        editButton.setIconStyle("add16");
        editButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                showEditDialog();
            }
        });
        toolbar.add(editButton);


        approveButton.setIconStyle("accept");
        approveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                confirmApprove();
            }
        });

        submitButton.setIconStyle("accept");
        submitButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                submitDocument();
            }
        });

        refreshButton.setIconStyle("refresh");
        refreshButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                refreshDocumentList(defaultParams);
            }
        });
        toolbar.add(editButton);
        toolbar.add(approveButton);
        toolbar.add(submitButton);
        toolbar.add(refreshButton);
        panel.setTopComponent(toolbar);
        panel.setFrame(false);
        panel.setBodyBorder(false);
        panel.setLayout(new FitLayout());
        panel.add(grid);

        panel.setWidth("100%");
        panel.setHeight(Window.getClientHeight());

        add(panel);
        defaultParams = "?module=wicid&action=getdocuments&mode=" + main.getMode();
        refreshDocumentList(defaultParams);
    }

    private void approveDocs() {
        String ids = "";
        for (ModelData row : selectedRows) {
            ids += row.get("docid") + ",";
        }
        String url =
                GWT.getHostPageBaseURL()
                + Constants.MAIN_URL_PATTERN + "?module=wicid&action=approvedocs&docids=" + ids;
        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, url);

        try {
            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot approve files", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        refreshDocumentList(defaultParams);
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot approve files", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot approve files", null);
        }
    }

    private void submitDocument() {

        FormPanel submitForm = new FormPanel();
        submitForm.setFrame(false);
        submitForm.setBodyBorder(false);
        submitForm.setHeight(150);
        submitForm.setWidth(400);
        submitForm.setLabelWidth(130);

        final Dialog submitDialog = new Dialog();
        submitDialog.setButtons(Dialog.OKCANCEL);

        submitCombo.setFieldLabel("Subit this document to");
        submitCombo.setEmptyText("Select who you want to submit to");
        submitCombo.add("Creator");
        submitCombo.add("APO");
        submitCombo.add("SubFaculty");
        submitCombo.add("Faculty");
        submitCombo.add("Senate");
        submitCombo.setTriggerAction(ComboBox.TriggerAction.ALL);
        submitCombo.setEditable(false);
        submitCombo.setAllowBlank(false);
        submitCombo.setForceSelection(true);
        submitCombo.addSelectionChangedListener(new SelectionChangedListener() {

            @Override
            public void selectionChanged(SelectionChangedEvent se) {
                status = submitCombo.getSelectedIndex();
            }
        });
        submitForm.add(submitCombo, formData);

        submitDialog.setBodyBorder(false);
        submitDialog.setHeading("Submitting");
        submitDialog.setWidth(400);
        submitDialog.setHeight(150);
        submitDialog.setHideOnButtonClick(true);
        submitDialog.setButtonAlign(HorizontalAlignment.CENTER);

        submitDialog.getButtonById(Dialog.OK).addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

                status = submitCombo.getSelectedIndex();
                statusS = submitCombo.getValue().getValue();

                String id = "";
                for (ModelData row : selectedRows) {
                    id = row.get("docid");
                }

                if (status > -1) {
                    String url =
                            GWT.getHostPageBaseURL()
                            + Constants.MAIN_URL_PATTERN + "?module=wicid&action=setstatus&docid=" + id + "&status=" + status;
                    RequestBuilder builder =
                            new RequestBuilder(RequestBuilder.GET, url);

                    try {
                        Request request = builder.sendRequest(null, new RequestCallback() {

                            public void onError(Request request, Throwable exception) {
                                MessageBox.info("Error", "Error, cannot change status", null);
                            }

                            public void onResponseReceived(Request request, Response response) {
                                if (200 == response.getStatusCode()) {
                                    MessageBox.info("Done", "The document has been submitted to " + statusS, null);
                                    refreshDocumentList(defaultParams);
                                } else {
                                    MessageBox.info("Error", "Error occured on the server. Cannot set status", null);
                                }
                            }
                        });
                    } catch (RequestException e) {
                        MessageBox.info("Fatal Error", "Fatal Error: cannot delete document", null);
                    }
                } else {
                    MessageBox.info("Error", "Pleave select a party to submit to.", null);
                }
            }
        });

        submitDialog.getButtonById(Dialog.CANCEL).addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                submitDialog.hide();
            }
        });

        submitDialog.add(submitForm);
        submitDialog.show();
    }

    private void deleteDocs() {
        String ids = "";
        for (ModelData row : selectedRows) {
            ids += row.get("docid") + ",";
        }
        String url =
                GWT.getHostPageBaseURL()
                + Constants.MAIN_URL_PATTERN + "?module=wicid&action=deletedocs&docids=" + ids;
        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, url);

        try {
            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot delete document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {

                        refreshDocumentList(defaultParams);
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot delete document", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot delete document", null);
        }
    }

    public void refreshDocumentList(String params) {
        params += "&userid=1";
        // defines the xml structure
        ModelType type = new ModelType();
        type.setRoot("documents");
        type.addField("userid", "userid");
        type.addField("group", "group");
        type.addField("docid", "docid");
        type.addField("Owner", "owner");
        type.addField("RefNo", "refno");
        type.addField("Title", "title");
        type.addField("Topic", "topic");
        type.addField("Department", "department");
        type.addField("Telephone", "telephone");
        type.addField("Date", "date");
        type.addField("Attachment", "attachmentstatus");
        type.addField("Status", "status");
        type.addField("currentuserid", "currentuserid");

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

    private void confirmApprove() {
        final Listener<MessageBoxEvent> l = new Listener<MessageBoxEvent>() {

            public void handleEvent(MessageBoxEvent ce) {
                Button btn = ce.getButtonClicked();
                if (btn.getText().equalsIgnoreCase("Yes")) {
                    // check if the document has an attachment
                    String attachments = "";
                    for (ModelData row : selectedRows) {
                        attachments += row.get("Attachment") + ",";
                    }

                    boolean status = false;
                    status = checkAttachments(attachments);
                    if (status) {
                        approveDocs();
                    } else {
                        MessageBox.info("Approve Documents", "Only documents that have attachemnts can be approved. Please try again!", null);
                    }
                }
            }
        };
        MessageBox.confirm("Confirm", "Are you sure you want to approve selected documents?", l);

    }

    private void confirmDelete() {
        final Listener<MessageBoxEvent> l = new Listener<MessageBoxEvent>() {

            public void handleEvent(MessageBoxEvent ce) {
                Button btn = ce.getButtonClicked();
                if (btn.getText().equalsIgnoreCase("Yes")) {
                    deleteDocs();
                }
            }
        };
        MessageBox.confirm("Confirm", "Are you sure you want to delete the selected documents?", l);

    }

    private void addContextMenu() {

        MenuItem editMenuItem = new MenuItem("Edit");
        editMenuItem.setIconStyle("edit");
        editMenuItem.addSelectionListener(new SelectionListener<MenuEvent>() {

            public void componentSelected(MenuEvent ce) {
                if (selectedRows.size() < 1) {
                    MessageBox.info("No documents", "Please, select document to edit", null);
                } else {
                    showEditDialog();
                }
            }
        });

        MenuItem approveMenuItem = new MenuItem("Approve");
        approveMenuItem.setIconStyle("accept");
        approveMenuItem.addSelectionListener(new SelectionListener<MenuEvent>() {

            public void componentSelected(MenuEvent ce) {
                if (selectedRows.size() < 1) {
                    MessageBox.info("No documents", "Please, select document(s) to approve", null);
                } else {
                    confirmApprove();
                }
            }
        });



        MenuItem submitMenuItem = new MenuItem("Submit");
        submitMenuItem.setIconStyle("accept");
        submitMenuItem.addSelectionListener(new SelectionListener<MenuEvent>() {

            public void componentSelected(MenuEvent ce) {
                submitDocument();
            }
        });
        MenuItem deleteMenuItem = new MenuItem("Delete");
        deleteMenuItem.setIconStyle("delete");
        deleteMenuItem.addSelectionListener(new SelectionListener<MenuEvent>() {

            public void componentSelected(MenuEvent ce) {
                if (selectedRows.size() < 1) {
                    MessageBox.info("No documents", "Please, select document(s) to delete", null);
                } else {
                    confirmDelete();
                }
            }
        });
        contextMenu.add(editMenuItem);
        contextMenu.add(approveMenuItem);
        contextMenu.add(submitMenuItem);
        contextMenu.add(new SeparatorMenuItem());
        contextMenu.add(deleteMenuItem);
    }

    private void showEditDialog() {

        Document document = new Document();
        document.setDate((String) selectedRows.get(0).get("date"));
        document.setRefNo((String) selectedRows.get(0).get("RefNo"));
        document.setOwnerName((String) selectedRows.get(0).get("Owner"));
        document.setDepartment((String) selectedRows.get(0).get("Department"));
        document.setTelephone((String) selectedRows.get(0).get("Telephone"));
        document.setTitle((String) selectedRows.get(0).get("Title"));
        document.setId((String) selectedRows.get(0).get("docid"));
        document.setGroup((String) selectedRows.get(0).get("group"));
        document.setTopic((String) selectedRows.get(0).get("Topic"));
        document.setAttachmentStatus((String) selectedRows.get(0).get("Attachment"));
        document.setStatus((String) selectedRows.get(0).get("status"));
        String mode = Constants.main.getMode();

        EditDocumentDialog editDocumentDialog = new EditDocumentDialog(document, mode, null);
        editDocumentDialog.show();
    }

    public void showEditDialog(final String docId) {

        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, GWT.getHostPageBaseURL()
                + Constants.MAIN_URL_PATTERN + "?module=wicid&action=getdocument&docid=" + docId);

        try {
            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, Cannot retrieve document details", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        JsArray<JSonDocument> doc = asArrayOfDocument(response.getText());
                        Document document = new Document();

                        if (doc.length() > 0) {

                            JSonDocument jSonDocument = doc.get(0);

                            if (jSonDocument.getAttachmentStatus() != null) {
                                if (jSonDocument.getAttachmentStatus().equals("Y")) {
                                    MessageBox.info("Complete", "This document cannot be edited because it has all the required information.", null);

                                    return;
                                }
                            }
                            if (jSonDocument.getOwner().equals("true")) {
                                document.setRefNo(jSonDocument.getRefNo());
                                document.setTitle(jSonDocument.getDocName());

                                document.setTopic(jSonDocument.getTopic());
                                document.setGroup(jSonDocument.getGroup());
                                document.setDepartment(jSonDocument.getTopic());
                                document.setOwnerName(jSonDocument.getOwnerName());
                                document.setTelephone(jSonDocument.getTelephone());
                                document.setId(docId);

                                EditDocumentDialog editDocumentDialog = new EditDocumentDialog(document, "limited", main);
                                editDocumentDialog.show();
                            } else {
                                MessageBox.info("Not owner", "Sorry, not owner, cannot edit the document", null);
                            }
                        }
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot retrieve document details", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: Cannot retrieve document details", null);
        }

    }

    /**
     * Convert the string of JSON into JavaScript object.
     */
    private final native JsArray<JSonDocument> asArrayOfDocument(String json) /*-{
    return eval(json);
    }-*/;

    private boolean checkAttachments(String attachments) {
        String[] attach = attachments.split(",");

        for (String a : attach) {
            if (a.equals("No")) {
                return false;
            }
        }

        return true;
    }

    /*  private int checkStatus() {
    System.out.println("checkStatus()");
    final String statusS = "";

    String ids = "";
    for (ModelData row : selectedRows) {
    ids += row.get("docid") + ",";
    }

    RequestBuilder builder =
    new RequestBuilder(RequestBuilder.GET, GWT.getHostPageBaseURL()+Constants.MAIN_URL_PATTERN +
    "?module=wicid&action=getstatus&docid=" + ids);

    try {
    Request request = builder.sendRequest(null, new RequestCallback() {

    public void onError(Request request, Throwable exception) {
    MessageBox.info("Error", "Error, Cannot retrieve document details", null);
    }

    public void onResponseReceived(Request request, Response response) {
    try {
    String data = response.getText();
    System.out.println(data);
    int status = Integer.parseInt(data);
    System.out.println(status);
    switch (status) {
    case 0:
    statusS.equals("Creation");
    case 1:
    statusS.equals("APO");
    case 2:
    statusS.equals("Subfaculty");
    case 3:
    statusS.equals("Faculty");
    case 4:
    statusS.equals("Senate");
    }
    } catch (NumberFormatException nfe) {
    MessageBox.info("Error", "Error, status is not an integer", null);
    }

    }
    });
    } catch (RequestException e) {
    MessageBox.info("Fatal Error", "Fatal Error: Cannot retrieve document details", null);
    }
    return status;
    }*/
}
