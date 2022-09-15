import 'package:flutter/material.dart';
import 'package:front/models/core/Rule.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/providers/CreateFolderProvider.dart';
import 'package:front/utils/ListType.dart';
import 'package:provider/provider.dart';

import 'DropDownListIcons.dart';

Widget CreateFolderForm(BuildContext context) {
  int id = 0;
  CreateFolderProvider createFolderProvider =
      Provider.of<CreateFolderProvider>(context, listen: false);
  FolderHelper folderHelper = FolderHelper(context: context);
  GlobalKey<FormState> createFolderFormKey = GlobalKey<FormState>();
  return Form(
    key: createFolderFormKey,
    child: Container(
      width: 600,
      margin: EdgeInsets.fromLTRB(20.0, 30.0, 20.0, 10.0),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(
            children: [
              Expanded(
                child: TextFormField(
                  maxLength: 25,
                  decoration: const InputDecoration(
                      labelText: 'Nom du dossier',
                      counterText: "",
                      hintText: 'Entrer du texte',
                      border: OutlineInputBorder()),
                  validator: (value) {
                    if (value == null) {
                      return 'Veuillez saisir un texte';
                    }
                    createFolderProvider.folder.name = value;
                    return null;
                  },
                ),
              ),
              DropDownListIcons(),
            ],
          ),
          Container(
            margin: EdgeInsets.only(top: 20, bottom: 5),
            child: Selector<CreateFolderProvider, List<Rule>>(
                selector: (_, provider) => provider.listRule,
                shouldRebuild: (previous, next) => true,
                builder: (_, listRule, __) {
                  List<Widget> listWidget = [];
                  for (Rule rule in listRule) {
                    TextEditingController textEditingController =
                        TextEditingController(text: rule.name);
                    listWidget.add(
                      Container(
                        margin: EdgeInsets.only(top: 5, bottom: 5),
                        child: Row(
                          children: [
                            IconButton(
                              onPressed: () {
                                createFolderProvider.listRule.removeWhere(
                                    (element) => rule.id == element.id);
                                createFolderProvider.listRule =
                                    createFolderProvider.listRule;
                              },
                              icon: Icon(Icons.close),
                            ),
                            Expanded(
                              child: TextFormField(
                                controller: textEditingController,
                                maxLength: 25,
                                decoration: const InputDecoration(
                                    labelText: 'Nom du Tag',
                                    counterText: "",
                                    hintText: 'Entrer du texte',
                                    border: OutlineInputBorder()),
                                onChanged: (String value) {
                                  rule.name = value;
                                },
                                validator: (value) {
                                  if (value == null) {
                                    return 'Veuillez saisir un texte';
                                  }
                                  return null;
                                },
                              ),
                            ),
                            Card(
                              color: Colors.grey[300],
                              margin: EdgeInsets.only(left: 5),
                              child: Container(
                                padding: EdgeInsets.symmetric(horizontal: 5),
                                child: DropdownButton(
                                    value: rule.type,
                                    items: listType
                                        .map<DropdownMenuItem<String>>(
                                            (String value) {
                                      return DropdownMenuItem<String>(
                                        value: value,
                                        child: Text(value),
                                      );
                                    }).toList(),
                                    onChanged: (String? newValue) {
                                      if (newValue == null) return;
                                      createFolderProvider.listRule
                                          .firstWhere((element) =>
                                              rule.id == element.id)
                                          .type = newValue;
                                      createFolderProvider.listRule =
                                          createFolderProvider.listRule;
                                    }),
                              ),
                            )
                          ],
                        ),
                      ),
                    );
                  }
                  return Column(
                    children: listWidget,
                  );
                }),
          ),
          Container(
            margin: EdgeInsets.symmetric(vertical: 20.0),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                ElevatedButton(
                  onPressed: () {
                    createFolderProvider.listRule.add(Rule(
                        id: id, name: "", type: "String", mandatory: false));
                    id++;
                    createFolderProvider.listRule =
                        createFolderProvider.listRule;
                  },
                  child: Text("Add Tag"),
                ),
                SizedBox(width: 50),
                ElevatedButton(
                  onPressed: () {
                    if (createFolderFormKey.currentState!.validate()) {
                      folderHelper.createFolder(
                        listRule: createFolderProvider.listRule,
                        value: createFolderProvider.folder,
                      );
                      Navigator.of(context).pop();
                    }
                  },
                  child: Text("Submit"),
                ),
              ],
            ),
          ),
        ],
      ),
    ),
  );
}
