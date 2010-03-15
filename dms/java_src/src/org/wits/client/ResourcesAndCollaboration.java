package org.wits.client;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.data.ModelData;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.ComboBox;
import com.extjs.gxt.ui.client.widget.form.ComboBox.TriggerAction;
import com.extjs.gxt.ui.client.widget.form.DateField;

import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextArea;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.layout.BorderLayout;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.i18n.client.DateTimeFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

/**
 *
 * @author nguni
 */
public class ResourcesAndCollaboration {
    private Dialog newResourcesDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private final TextField<String> E1a = new TextField<String>();
    private final TextField<String> E1b = new TextField<String>();
    private final TextField<String> E2a = new TextField<String>();
    private final TextField<String> E2b = new TextField<String>();
    private final TextField<String> E2c = new TextField<String>();
    private final TextField<String> E3a = new TextField<String>();
    private final TextField<String> E3b = new TextField<String>();
    private final TextField<String> E3c = new TextField<String>();
    private final TextField<String> E4 = new TextField<String>();
    private final TextField<String> E5a = new TextField<String>();
    private final TextField<String> E5b = new TextField<String>();
    private Button saveButton = new Button("Save");
    private String title = "";

    public ResourcesAndCollaboration() {
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(700);
        mainForm.setLabelWidth(400);

        E1a.setFieldLabel("E.1.a Is there currently adequate teaching capacity with regard to the introduction of the course/unit?");
        E1a.setAllowBlank(false);
        E1a.setName("E1a");

        E1b.setFieldLabel("E.1.b Who will teach the course/unit?");
        E1b.setAllowBlank(false);
        E1b.setName("E1b");

        E2a.setFieldLabel("E.2.a How many students will the course/unit attract?");
        E2a.setAllowBlank(false);
        E2a.setName("E2a");

        E2b.setFieldLabel("E.2.a How has this been factored into the enrolment planning in your Faculty?");
        E2b.setAllowBlank(false);
        E2b.setName("E2b");

        E2c.setFieldLabel("E.2.c How has it been determined if the course/unit is sustainable in the long term, or short term if of topical interest?");
        E2c.setAllowBlank(false);
        E2c.setName("E2c");

        E3a.setFieldLabel("E.3.a Specify the space requirements for the course/unit.");
        E3a.setAllowBlank(false);
        E3a.setName("E3a");

        E3b.setFieldLabel("E.3.b Specify the IT teaching resources required for the course/unit.");
        E3b.setAllowBlank(false);
        E3b.setName("E3b");

        E3c.setFieldLabel("E.3.c Specify the library resources required to teach the course/unit.");
        E3c.setAllowBlank(false);
        E3c.setName("E3c");
        
        E4.setFieldLabel("E.4 Does the School intend to offer the course/unit in addition to its current course/unit offering, or is the intention to eliminate an existing course/unit?");
        E4.setAllowBlank(false);
        E4.setName("E4");

        E5a.setFieldLabel("E.5.a Specify the name of the course/unit co-ordinator.");
        E5a.setAllowBlank(false);
        E5a.setName("E5a");

        E5b.setFieldLabel("E.5.b State the Staff number of the course/unit coordinator (consult your Faculty Registrar)");
        E5b.setAllowBlank(false);
        E5b.setName("E5b");

        mainForm.add(E1a, formData);
        mainForm.add(E1b, formData);
        mainForm.add(E2a, formData);
        mainForm.add(E2b, formData);
        mainForm.add(E2c, formData);
        mainForm.add(E3a, formData);
        mainForm.add(E3b, formData);
        mainForm.add(E3c, formData);
        mainForm.add(E4, formData);
        mainForm.add(E5a, formData);
        mainForm.add(E5b, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

                title = E1a.getValue();
                if (title == null) {
                    MessageBox.info("Missing title", "Provide title", null);
                    return;
                }
                if (title.trim().equals("")) {
                    MessageBox.info("Missing title", "Provide title", null);
                    return;
                }
            }
        });
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        
        newResourcesDialog.setBodyBorder(false);
        newResourcesDialog.setHeading("Section E: Resources");
        newResourcesDialog.setWidth(800);
        //newResourcesDialog.setHeight(450);
        newResourcesDialog.setHideOnButtonClick(true);
        newResourcesDialog.setButtons(Dialog.CLOSE);
        newResourcesDialog.setButtonAlign(HorizontalAlignment.LEFT);

        newResourcesDialog.add(mainForm);
    }

    public void show() {
        newResourcesDialog.show();
    }
}
