import 'package:flutter/material.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/core/Rule.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/models/helper/RuleHelper.dart';
import 'package:front/providers/UpdateFolderProvider.dart';
import 'package:front/utils/ListIcons.dart';
import 'package:front/utils/ListType.dart';
import 'package:provider/provider.dart';

import 'DropDownListIcons.dart';

Widget updateFolderForm({
  required BuildContext context,
  required Folder folder,
  required List<Rule> listRule,
}) {
  UpdateFolderProvider updateFolderProvider =
      Provider.of<UpdateFolderProvider>(context, listen: false);
  FolderHelper folderHelper = FolderHelper(context: context);
  GlobalKey<FormState> updateFolderFormKey = GlobalKey<FormState>();

  updateFolderProvider.setListRule = listRule;
  return Form(
    key: updateFolderFormKey,
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
                  initialValue: folder.name,
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
                    folder.name = value;
                    return null;
                  },
                ),
              ),
              Selector<UpdateFolderProvider, int>(
                selector: (_, provider) => provider.indexIcon,
                builder: (_, indexIcon, __) {
                  return Card(
                    color: Colors.grey[300],
                    child: Container(
                      margin: EdgeInsets.symmetric(horizontal: 10.0),
                      child: DropdownButton<IconData>(
                        value: listIcons[folder.iconIndex],
                        icon: const Icon(Icons.arrow_downward),
                        elevation: 16,
                        underline: Container(
                          height: 2,
                        ),
                        onChanged: (IconData? newValue) {
                          if (newValue == null) return;
                          folder.iconIndex = listIcons.indexOf(newValue);
                          updateFolderProvider.indexIcon =
                              listIcons.indexOf(newValue);
                        },
                        items: listIcons
                            .map<DropdownMenuItem<IconData>>((IconData value) {
                          return DropdownMenuItem<IconData>(
                            value: value,
                            child: Icon(value),
                          );
                        }).toList(),
                      ),
                    ),
                  );
                },
              ),
            ],
          ),
          Container(
            margin: EdgeInsets.only(top: 20, bottom: 5),
            child: Selector<UpdateFolderProvider, List<Rule>>(
                selector: (_, provider) => provider.listRule,
                shouldRebuild: (previous, next) => true,
                builder: (_, listRuleProvider, __) {
                  List<Widget> listWidget = [];
                  for (Rule rule in listRuleProvider) {
                    TextEditingController textEditingController =
                        TextEditingController(text: rule.name);
                    listWidget.add(
                      Container(
                        margin: EdgeInsets.only(top: 5, bottom: 5),
                        child: Row(
                          children: [
                            IconButton(
                              onPressed: () async {
                                RuleHelper ruleHelper =
                                    RuleHelper(context: context);

                                if (!await ruleHelper.deleteRule(
                                  idRule: rule.id,
                                  idFolder: folder.id,
                                )) return;

                                updateFolderProvider.listRule.removeWhere(
                                    (element) => rule.id == element.id);
                                updateFolderProvider.listRule =
                                    updateFolderProvider.listRule;
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
                                      updateFolderProvider.listRule
                                          .firstWhere((element) =>
                                              rule.id == element.id)
                                          .type = newValue;
                                      updateFolderProvider.listRule =
                                          updateFolderProvider.listRule;
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
                  onPressed: () async {
                    RuleHelper ruleHelper = RuleHelper(context: context);
                    Rule? rule = await ruleHelper.createFolderRule(
                        rule: Rule(
                            id: 0,
                            name: "",
                            type: listType[0],
                            mandatory: false),
                        idFolder: folder.id);
                    if (rule == null) return;
                    updateFolderProvider.listRule.add(rule);
                    updateFolderProvider.listRule =
                        updateFolderProvider.listRule;
                  },
                  child: Text("Add Tag"),
                ),
                SizedBox(width: 50),
                ElevatedButton(
                  onPressed: () async {
                    if (updateFolderFormKey.currentState!.validate()) {
                      folderHelper.updateFolder(
                        idFolder: folder.id,
                        folder: folder,
                        listRule: updateFolderProvider.listRule,
                      );

                      // ignore: use_build_context_synchronously
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
