import 'package:flutter/material.dart';
import 'package:front/providers/CreateFolderProvider.dart';
import 'package:front/utils/ListIcons.dart';
import 'package:provider/provider.dart';

class DropDownListIcons extends StatefulWidget {
  const DropDownListIcons({Key? key}) : super(key: key);

  @override
  State<DropDownListIcons> createState() => _DropDownListIcons();
}

class _DropDownListIcons extends State<DropDownListIcons> {
  late CreateFolderProvider createFolderProvider;

  @override
  void initState() {
    super.initState();
    createFolderProvider =
        Provider.of<CreateFolderProvider>(context, listen: false);
  }

  @override
  Widget build(BuildContext context) {
    return Selector<CreateFolderProvider, int>(
        selector: (_, provider) => provider.indexIcon,
        builder: (_, indexIcon, __) {
          return Card(
            color: Colors.grey[300],
            child: Container(
              margin: EdgeInsets.symmetric(horizontal: 10.0),
              child: DropdownButton<IconData>(
                value: listIcons[indexIcon],
                icon: const Icon(Icons.arrow_downward),
                elevation: 16,
                underline: Container(
                  height: 2,
                ),
                onChanged: (IconData? newValue) {
                  if (newValue == null) return;
                  createFolderProvider.indexIcon = listIcons.indexOf(newValue);
                  createFolderProvider.folder.iconIndex =
                      listIcons.indexOf(newValue);
                },
                items:
                    listIcons.map<DropdownMenuItem<IconData>>((IconData value) {
                  return DropdownMenuItem<IconData>(
                    value: value,
                    child: Icon(value),
                  );
                }).toList(),
              ),
            ),
          );
        });
  }
}
